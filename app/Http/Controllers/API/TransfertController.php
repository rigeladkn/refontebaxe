<?php

namespace App\Http\Controllers\API;

use App\Models\Pays;
use App\Models\User;
use App\Models\Transfert;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\TauxTrait;
use App\Http\Traits\FraisTrait;
use App\Http\Traits\SoldesTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Notifications\TransfertCreate;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreTransfertRequest;

class TransfertController extends Controller
{
    use FraisTrait, TauxTrait, SoldesTrait;

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $transferts_from = auth()->user()->client->transferts_from;

        $transferts_to = auth()->user()->client->transferts_to;

        $transferts = collect($transferts_from)->merge($transferts_to)->sortByDesc('created_at');

        foreach ($transferts as $transfert)
        {
            if ($transfert->user_from->id != auth()->user()->id)
            {
                $transfert->montant = ($transfert->montant * $transfert->taux_to) - $transfert->frais;

                $transfert->user_from_nom = $transfert->user_from->noms();
            }
            else
            {
                $transfert->user_to_nom = $transfert->user_to->noms();
            }
        }

        return $transferts;
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $request->merge([
            'destinataire' => $request->pays.$request->destinataire,
        ]);

        $regles = [
            "pays"            => ['required', 'exists:pays,indicatif'],
            "destinataire"    => ['required', 'exists:users,telephone'],
            "montant"         => ['required', 'numeric', 'integer', 'min:1'],
            // "code_validation" => ['required'],
        ];

        $messages = [
            'destinataire.exists' => 'Le destinataire n\'est pas client '.env('APP_NAME'),
        ];

        $destinataire = User::where('telephone', $request->destinataire)->first();

        $validator = Validator::make($request->all(), $regles, $messages);

        $validator->after(function ($validator) use ($request, $destinataire) {

            if ($destinataire)
            {
                if (auth()->id() == $destinataire->id)
                {
                    $validator->errors()->add('destinataire', 'Impossible de faire le transfert vers cette destination.');
                }

                // On check le type de transfert lorsqu'il n'est pas entrain de demandé le resumé
                if (!$request->resume)
                {
                    if ($request->payement_method != 'solde' && $request->payement_method != 'carte')
                    {
                        $validator->errors()->add('payement_method', 'Méthode de paiement invalide');
                    }

                    if ($request->payement_method == 'carte')
                    {
                        if (!$request->paymentMethodId)
                        {
                            $validator->errors()->add('paymentMethodId', 'Impossible de faire cette transaction sans carte de paiement');
                        }
                    }
                }

                /**
                * *S'il a les fonds suffisant
                */
                // On check s'il a solde s'il est entrain de faire un transfert par solde
                if ($request->payement_method == 'solde')
                {
                    if ($this->not_required_solde($request->montant) || $this->not_required_solde($request->montant))
                    {
                        $validator->errors()->add('montant', 'Solde insuffisant');
                    }  
                }

                // if (!Hash::check($request->code_validation, auth()->user()->code_validation))
                // {
                //     $validator->errors()->add('code_validation', 'Code de validation invalide');
                // }
            }
        });

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $transfert_par_solde = true;

        $frais = $this->frais_get_frais_transfert(Transfert::class, auth()->user(), $destinataire);

        $montant_frais = $frais->frais_fixe ?: convertir_un_pourcentage_en_nombre($frais->frais_pourcentage, $request->montant);

        $taux_to = $this->taux_fetch_one(auth()->user()->pays->symbole_monnaie, $destinataire->pays->symbole_monnaie);

        $montant_envoyer = $request->montant;
        
        //check if could send money with fees
        if( $this->not_required_solde($montant_envoyer + $montant_frais)){
            $validator->errors()->add('montant', 'Solde insuffisant');
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Montant que le destinataire recevra en sa monnaie
        $montant_recu = $this->taux_convert(auth()->user()->pays->symbole_monnaie, $destinataire->pays->symbole_monnaie, $request->montant);

        if ($this->user_europe_to_user_afrique(auth()->user()->pays->continent, $destinataire->pays->continent))
        {
            sscanf($montant_recu, '%d%f', $int, $float);

            // TODO BAXE recoit le $float

            $montant_recu = $int + 1;
        }

        if ($request->resume == 1)
        {
            return response([
                'data' => [
                    'destinataire'            => $destinataire->noms(),
                    'frais'                   => round($montant_frais, 2),
                    'montant_total_transfert' => round($montant_envoyer + $montant_frais, 2),
                    'destinataire_recoit'     => $montant_recu,
                    'monnaie_expediteur'      => auth()->user()->pays->symbole_monnaie,
                    'monnaie_destinataire'    => $destinataire->pays->symbole_monnaie,
                ]
            ], 200);
        }

        if ($request->payement_method == 'carte')
        {
            $transfert_par_solde = false;
        }

        // dd($request->montant);

        if ($transfert_par_solde == false)
        {
            try
            {
                $frais_suppelementaire = convertir_un_pourcentage_en_nombre(2, $request->montant);

                if (auth()->user()->pays->continent != 'Africa')
                {
                    $montant = $montant_envoyer * 100;

                    $frais_suppelementaire = $frais_suppelementaire * 100;
                }

                $montant = $montant + $frais_suppelementaire;

                $montant = round($montant, 2);

                $stripeCharge = $request->user()->charge($montant, $request->paymentMethodId, [
                    'currency' => auth()->user()->pays->symbole_monnaie,
                    'description' => 'Transfert de '.format_number_french($request->montant).' '.auth()->user()->pays->symbole_monnaie.' à '.$destinataire->noms(),
                    'receipt_email' => $request->user()->email
                ]);
            }
            catch (\Throwable $th)
            {
                dd($th);
                return response([
                    'message' => "Transfert échoué. Veuillez réessayer plus tard."
                ], 403);
            }
        }

        $this->transfert(auth()->user(), $destinataire, $montant_envoyer, $montant_frais, $taux_to, $montant_recu, 1, $transfert_par_solde);

        $message = 'Vous venez d’envoyer '.format_number_french($request->montant, 2).' '.auth()->user()->pays->symbole_monnaie.' à '.$destinataire->noms().' via '.env('APP_NAME').'. Votre nouveau  solde : '.format_number_french(auth()->user()->soldes->last()->actuel).' '.auth()->user()->pays->symbole_monnaie.'. '.env('APP_NAME').' vous remercie pour votre fidélité.';

        return response()->json([
            'message' => $message,
            'solde' => $this->get_solde()
        ], 200);
    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        //
    }

    /**
     * Si on precise pas les frais, les taux c'est qu'ils sont dans le meme pays
     * Sinon on precise
     *
     * @param  mixed $user_from
     * @param  mixed $user_to
     * @param  mixed $montant_envoyer
     * @param  mixed $frais
     * @param  mixed $taux_to
     * @param  mixed $montant_recu
     * @param  mixed $taux_from
     * @param  mixed $transfert_par_solde
     * @return void
     */
    protected function transfert(User $user_from, User $user_to, $montant_envoyer, $frais, $taux_to, $montant_recu, $taux_from = 1, $transfert_par_solde = true)
    {
        $transfert = Transfert::create([
            'reference'    => Str::random(10),
            'user_id_from' => $user_from->id,
            'user_id_to'   => $user_to->id,
            'montant'      => $montant_envoyer,
            'frais'        => $frais,
            'taux_from'    => $taux_from,
            'taux_to'      => $taux_to,
            'pays_from'    => env('APP_ENV') == 'production' ? $this->get_geolocation()['country_code2'] : $user_from->pays->code,
            'pays_to'      => $user_to->pays->code,
            'ip_from'      => env('APP_ENV') == 'production' ? request()->ip() : $user_from->ip_register,
            'ip_to'        => $user_to->recent_ip
        ]);

        // TODO Recuperation des beneficies liés aux frais et aux taux

        if ($transfert)
        {
            $this->set_solde($user_from, $transfert->id, Transfert::class, $transfert_par_solde ? $this->new_solde_user_is_from($montant_envoyer + $frais) : ($this->get_solde() ? $this->get_solde()->actuel : 0));

            $this->set_solde($user_to, $transfert->id, Transfert::class, $this->new_solde_user_is_to($user_to, $montant_recu));

            $libelle_user_from = 'Transfert à '.$user_to->noms().'.';

            $message_user_from = 'Vous avez transférer '.format_number_french($montant_envoyer, 2).' '.$user_from->pays->symbole_monnaie.' à '.$user_to->noms().' via '.env('APP_NAME').'.<br><br> Votre nouveau  solde : '.format_number_french($user_from->soldes->last()->actuel).' '.$user_from->pays->symbole_monnaie.'.';

            /**
             * user_from à true pour affiché la facture
             * user_to à false pour ne pas afficher la facture et affiché que le mail markdown de laravel
             */
            $params = ['transfert_mode' => $transfert_par_solde ? 'Solde '.env('APP_NAME') : 'Carte de crédit/débit', 'montant_recu' => $montant_recu, 'user_from' => true, 'user_to' => false];

            $user_from->notify(new TransfertCreate($transfert, $user_from, $user_to, $libelle_user_from, $message_user_from, $params));

            $libelle_user_to = 'Transfert de '.$user_from->noms().'.';

            $message_user_to = 'Vous avez reçu '.format_number_french($montant_recu, 2).' '.$user_to->pays->symbole_monnaie.' de '.$user_from->noms().' via '.env('APP_NAME').'.<br><br> Votre nouveau  solde : '.format_number_french($user_to->soldes->last()->actuel).' '.$user_to->pays->symbole_monnaie.'.';

            /**
             * user_to a true pour afficher le mail markdown de laravel ainsi
             * user_from false pour ne pas afficher la facture pour le destinataire
             */
            $params = ['user_to' => true, 'user_from' => false];

            $user_to->notify(new TransfertCreate($transfert, $user_from, $user_to, $libelle_user_to, $message_user_to, $params));
        }
    }

    public function get_taux($pays_indicatif)
    {
        $pays = Pays::where('indicatif', $pays_indicatif)->first();

        if (!$pays)
        {
            return response()->json(['errors' => ['pays' => "Pays non trouvé"]], 404);
        }

        $taux = $this->taux_fetch_one(auth()->user()->pays->symbole_monnaie, $pays->symbole_monnaie);

        return response()->json(['data' => [
            'destinataire_taux' => $taux,
            'destinataire_monnaie' => $pays->symbole_monnaie,
        ]], 200);
    }

    public function get_destinataire($numero_telephone)
    {
        $destinataire = User::where('telephone', $numero_telephone)->first();

        if (!$destinataire || $destinataire->id == auth()->id())
        {
            return response()->json(['errors' => ['destinataire' => 'Le destinataire n\'est pas client '.env('APP_NAME')]], 404);
        }

        $frais = $this->frais_get_frais_transfert(Transfert::class, auth()->user(), $destinataire);

        $frais = [
            'frais_pourcentage' => $frais->frais_pourcentage,
            'frais_fixe'        => $frais->frais_fixe,
        ];

        return response()->json(['data' => [
            'destinataire' => $destinataire->client,
            'frais'        => $frais
        ]], 200);
    }

}
