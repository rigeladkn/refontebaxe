<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Depot;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\TauxTrait;
use App\Http\Traits\FraisTrait;
use App\Http\Traits\SoldesTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\LocalisationTrait;
use Illuminate\Support\Facades\Validator;

class DepotController extends Controller
{
    use SoldesTrait, TauxTrait, LocalisationTrait, FraisTrait;

    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'client_id'       => ['required', 'exists:users,id'],
            'client_montant'  => ['required', 'integer', 'min:1'],
            'resume'          => ['required', 'string', 'in:0,1'],
            // 'code_validation' => ['required'],
        ]);

        $client = User::find($request->client_id);

        $validator->after(function ($validator) use ($client) {
            if (request()->resume == 0)
            {
                if ($this->not_required_solde(request()->montant))
                {
                    $validator->errors()->add('montant', 'Solde insuffisant');
                }

                if (request()->montant && !is_numeric(request()->montant))
                {
                    $validator->errors()->add('montant', 'Montant invalide');
                }

                if (request()->resume == 0 && ! request()->montant)
                {
                    $validator->errors()->add('montant', "Le champ est obligatoire.");
                }

                $montant = request()->montant;

                if (!$this->same_country_users(auth()->user(), $client))
                {
                    $montant = $this->taux_convert(auth()->user()->pays->symbole_monnaie, $client->pays->symbole_monnaie, $montant);
                }

                if ( ! ($this->frais_get_commission_depot_distributeur(Depot::class, auth()->user(), $montant)) && $montant > 0)
                {
                    $validator->errors()->add('exeption_error', "Désolé vous ne pouvez pas effectuer cette opération. \n Si vous pensez qu'il s'agit d'une erreur contacter le service client.");
                }

                // if (!Hash::check(request()->code_validation, auth()->user()->code_validation))
                // {
                //     $validator->errors()->add('code_validation', 'Code de validation invalide');
                // }
            }

            if (Gate::forUser($client)->denies('is-client'))
            {
                $validator->errors()->add('client_id', "Impossible de faire le dépôt pour ce client.");
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $montant = $request->client_montant;

        if (!$this->same_country_users(auth()->user(), $client))
        {
            $montant = $this->taux_convert($client->pays->symbole_monnaie, auth()->user()->pays->symbole_monnaie, $request->client_montant);
        }

        if ($request->resume == 1)
        {
            $client->montant = $montant;

            $client->nom_prenoms = $client->noms();

            return response()->json([
                'data' => $client,
            ], 200);
        }

        $taux_to = $this->taux_fetch_one(auth()->user()->pays->symbole_monnaie, $client->pays->symbole_monnaie);

        $commission = $this->frais_get_commission_depot_distributeur(Depot::class, auth()->user(), $request->montant);

        $commission = $commission->frais_fixe ?: convertir_un_pourcentage_en_nombre($commission->frais_pourcentage, $request->montant);

        $depot = Depot::create([
            'reference'    => Str::random(10),
            'user_id_from' => auth()->user()->id,
            'user_id_to'   => $client->id,
            'montant'      => $request->montant,
            'frais'        => 0,
            'taux_from'    => 1,
            'taux_to'      => $taux_to,
            'pays_from'    => env('APP_ENV') == 'production' ? $this->get_geolocation()['country_code2'] : auth()->user()->pays->code,
            'pays_to'      => $client->pays->code,
            'ip_from'      => env('APP_ENV') == 'production' ? request()->ip() : auth()->user()->ip_register,
            'ip_to'        => $client->recent_ip
        ]);

        if ($depot)
        {
            $this->set_solde(auth()->user(), $depot->id, Depot::class, $this->new_solde_user_is_from($request->montant));

            if (!$this->same_country_users(auth()->user(), $client))
            {
                $montant = $this->taux_convert(auth()->user()->pays->symbole_monnaie, $client->pays->symbole_monnaie, $request->montant);
            }

            $this->set_solde($client, $depot->id, Depot::class, $this->new_solde_user_is_to($client, $montant));

            auth()->user()->commissions()->create([
                'operation_type' => Depot::class,
                'operation_id' => $depot->id,
                'commission' => $commission
            ]);
        }

        $commission_depot = auth()->user()->commissions->where('operation_type', Depot::class)->where('statut', false)->sum('commission');

        $message = 'Vous venez de faire un dépôt de '.format_number_french($request->montant, 2).' '.auth()->user()->pays->symbole_monnaie.' à '.$client->noms().' via '.env('APP_NAME').'. Votre nouveau  solde : '.format_number_french(auth()->user()->soldes->last()->actuel, 2).' '.auth()->user()->pays->symbole_monnaie.'. '.env('APP_NAME').' vous remercie pour votre collaboration.';

        return response()->json([
            'solde' => $this->get_solde(),
            'commission_depot' => $commission_depot,
            'message' => $message
        ], 200);
    }

    public function carte_credit(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'montant' => ['required', 'numeric', 'min:10', 'max:99999999'],
            'paymentMethodId' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (auth()->user()->pays->continent == 'Africa')
        {
            abort(403);
        }

        $message = '';

        try
        {
            $frais_suppelementaire = convertir_un_pourcentage_en_nombre(2, $request->montant);

            if (auth()->user()->pays->continent != 'Africa')
            {
                $montant = $request->montant * 100;

                $montant = $montant + ($frais_suppelementaire * 100);
            }

            $montant = round($montant, 2);

            $stripeCharge = 
            true;
            /*$request->user()->charge($montant, $request->paymentMethodId, [
                'currency' => auth()->user()->pays->symbole_monnaie,
                'description' => 'Rechargement de '.format_number_french($request->montant).' '.auth()->user()->pays->symbole_monnaie.' par '.auth()->user()->noms(),
                'receipt_email' => $request->user()->email
            ]);*/

            if ($stripeCharge)
            {
                $depot = Depot::create([
                    'reference'  => Str::random(10),
                    'user_id_from' => auth()->user()->id,
                    'user_id_to' => auth()->user()->id,
                    'montant'    => $request->montant,
                    'frais'      => $frais_suppelementaire,
                    'taux_from'  => 1,
                    'taux_to'    => 1,
                    'pays_from'  => env('APP_ENV') == 'production' ? $this->get_geolocation()['country_code2'] : auth()->user()->pays->code,
                    'pays_to'    => auth()->user()->pays->code,
                    'ip_from'    => env('APP_ENV') == 'production' ? request()->ip() : auth()->user()->ip_register,
                    'ip_to'      => auth()->user()->recent_ip
                ]);

                $this->set_solde(auth()->user(), $depot->id, Depot::class, $this->new_solde_user_is_to(auth()->user(), $request->montant));

                $message = 'Vous venez de recharger votre compte de '.format_number_french($request->montant, 2).' '.auth()->user()->pays->monnaie.'. Votre nouveau solde : '.format_number_french(auth()->user()->soldes->last()->actuel, 2).' '.auth()->user()->pays->monnaie.'.'.env('APP_NAME').' vous remercie pour votre fidélité.';

                return response()->json([
                    'solde' => $this->get_solde(),
                    'message' => $message
                ], 200);
            }
        }
        catch (\Throwable $th)
        {
            return response()->json([
                'message' => "Le rechargement a échoué. Veuillez réessayer."
            ], 403);
        }
    }
}
