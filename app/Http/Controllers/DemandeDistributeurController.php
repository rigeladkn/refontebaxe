<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Pays;
use App\Models\User;
use App\Models\Distributeur;
use Illuminate\Support\Str;
use App\Models\DemandeDistributeur;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\LocalisationTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Notification;
use App\Notifications\EmailNotification;
use App\Mail\SendMail;
use Mail;
use Illuminate\Pagination\Paginator;

class DemandeDistributeurController extends Controller
{
    use LocalisationTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $demandes = DemandeDistributeur::where('status_demande', 0)->orderByDesc('created_at')->paginate(20);

        return view('auth.list-demande-distributeur')->with('demandes', $demandes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
    * Merci de lui fournir les donnees suivantes
    * @param $nom
    * @param $prenoms
    * @param $code_postal
    * @param $ville
    * @param $email
    * @param $telephone (telephone2 et telephone3 sont facultatif)
    * @param $activite_principale
    * @param $entreprise_nom
    * @param $path_piece_identite
    * @param $password
    * @param $password_confirmation
    * @param $registre_commerce  (facultatif)
    * @param $path_media_du_local  (facultatif)
    * @param $communication_baxe (facultatif)
    *
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom'                   => ['required', 'string', 'max:255'],
            'prenoms'               => ['required', 'string', 'max:255'],
            'code_postal'           => ['required', 'string', 'max:255'],
            'ville'                 => ['required', 'string', 'max:255'],
            'entreprise_nom'        => ['required', 'string', 'max:255'],
            // 'path_media_du_local'   => ['nullable', 'array', 'min:4', 'max:10'],
            // 'path_media_du_local.*' => ['required', 'file', 'mimes:jpg,jpeg,png,bmp,gif,svg', 'max:10024'],
            // 'path_piece_identite'   => ['required', 'array', 'min:1', 'max:2'],
            // 'path_piece_identite.*' => ['required', 'file', 'mimes:docx,pdf', 'max:10024'],
            'email'                 => ['required', 'email', 'max:255', 'unique:demande_distributeurs,email', 'unique:users,email'],
        ]);

        $validator->after(function ($validator) use ($request) {
            // Localisation curent user
            $adress_datas = $this->get_geolocation();

            // recuperation pays user via code pour avoir l'identifiant du pays
            $paysUser = Pays::where('code', $adress_datas['country_code2'])->first();

            $request->merge([
                'telephone2' => empty($request->telephone2) ? "" : $paysUser->indicatif.$request->telephone2,
                'telephone3' => empty($request->telephone3) ? "" : $paysUser->indicatif.$request->telephone3,
            ]);

            if (str_starts_with($request->telephone, '00') || str_starts_with($request->telephone, '+'))
            {
                $validator->errors()->add('telephone', "La valeur du champ doit être saisi sans l'indicatif du pays.");
            }

            $telephone = $paysUser->indicatif.$request->telephone;

            if (DemandeDistributeur::where('telephone', $telephone)->first() || User::where('telephone', $telephone)->first())
            {
                $validator->errors()->add('telephone', "La valeur du champ est déjà utilisée.");
            }

            if (str_starts_with($request->telephone2, '00') || str_starts_with($request->telephone2, '+'))
            {
                $validator->errors()->add('telephone2', "La valeur du champ doit être saisi sans l'indicatif du pays.");
            }

            if (str_starts_with($request->telephone3, '00') || str_starts_with($request->telephone3, '+'))
            {
                $validator->errors()->add('telephone3', "La valeur du champ doit être saisi sans l'indicatif du pays.");
            }
        });

        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $adress_datas = $this->get_geolocation();

        // recuperation pays user via code pour avoir l'identifiant du pays
        $paysUser = Pays::where('code', $adress_datas['country_code2'])->first();

        $base_path = 'demande_distributeurs/'.strtolower(str_replace(' ', '_', $request->nom.' '.$request->prenoms)).'-'.$request->telephone.'/';

        $path_media_du_local = storage_file($request, $base_path.'photos_local', 'path_media_du_local');

        $path_piece_identite = storage_file($request, $base_path.'photos_ducument_identite', 'path_piece_identite');
        // nouvel demande distributeur
        $distributeur = DemandeDistributeur::create([
            'pays_register_id'    => $paysUser->id,
            'ip_register'         => $adress_datas['ip'],
            'recent_ip'           => $adress_datas['ip'],
            'nom'                 => ucwords(strtolower($request->nom)),
            'prenoms'             => ucwords(strtolower($request->prenoms)),
            'code_postal'         => $request->code_postal,
            'ville'               => $request->ville,
            'email'               => strtolower(trim($request->email)),
            'telephone'           => str_replace(' ', '', $paysUser->indicatif.$request->telephone),
            'telephone2'          => empty($request->telephone2) ? "" : str_replace(' ', '', $paysUser->indicatif.$request->telephone2),
            'telephone3'          => empty($request->telephone3) ? "" : str_replace(' ', '', $paysUser->indicatif.$request->telephone3),
            'activite_principale' => ucfirst(strtolower($request->activite_principale)),
            'registre_commerce'   => $request->registre_commerce,
            'entreprise_nom'      => ucwords(strtolower($request->entreprise_nom)),
            'path_piece_identite' => json_encode($path_piece_identite),
            'path_media_du_local' => json_encode($path_media_du_local),
            'communication_baxe'  => ucwords(strtolower($request->communication_baxe)),
        ]);
        /**
        * TODO Faire affiché les images en JSON uploadé (Boubacar)
        */
        return redirect()->back()->with('message', 'Votre demande a été envoyé avec succès. Nous vous contacterons dans les plus brefs délais. Merci.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $demande = DemandeDistributeur::find($id);
        return view('auth.affiche-demande-distributeur')->with('demande', $demande);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if($request->action == 2){
            $validator = Validator::make($request->all(), [
                'raison' => ['required', 'string', 'max:255'],
            ]);
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }
        $demande = DemandeDistributeur::find($id);

        if($request->action == 1){
            //nouvel user pour les infos de connexion
            $user = new User();
            $passwordGenered = Str::random(10);
            $user = User::create([
                'pays_register_id' => $demande->pays_register_id,
                'ip_register'      => $demande->ip_register,
                'email'            => strtolower(trim($demande->email)),
                'telephone'        => trim($demande->telephone),
                'recent_ip'        => $demande->recent_ip,
                'password'         => Hash::make($passwordGenered),
            ]);

            $distributeur = new Distributeur();
            // nouvel distributeur
            $distributeur = Distributeur::create([
                'reference'             => Str::random(10),
                'pays_register_id'      => $demande->pays_register_id,
                'ip_register'           => $demande->ip_register,
                'recent_ip'             => $demande->recent_ip,
                'nom'                   => $demande->nom,
                'prenoms'               => $demande->prenoms,
                'code_postal'           => $demande->code_postal,
                'ville'                 => $demande->ville,
                'email'                 => $demande->email,
                'telephone'             => $demande->telephone,
                'telephone2'            => $demande->telephone2,
                'telephone3'            => $demande->telephone3,
                'activite_principale'   => $demande->activite_principale,
                'registre_commerce'     => $demande->registre_commerce,
                'num_compte_bancaire'   => $demande->num_compte_bancaire,
                'entreprise_nom'        => $demande->entreprise_nom,
                'path_piece_identitite' => $demande->path_piece_identite,
                'path_media_du_local'   => $demande->path_media_du_local,
                'communication_baxe'    => $demande->communication_baxe,
                'user_id'               => $user->id,
                'pays_id'               => $demande->pays_register_id,
            ]);

            $demande->status_demande = 1;
            $demande->save();
            // event(new Registered($user));
            // Envoie d'email
            $action = "d'accepter";
            $donnees = [
                'subject'     => 'Validation de la demande',
                'greeting'    => 'Salut '.$demande->prenoms.',',
                'body'        => "Votre demande de compte distributeur chez BaxeMoneyTransfer viens d'être acceptée Pour vous connecté merci d'utiliser ces coordonée email: $demande->email et mot de passe: $passwordGenered",
                'urlText'     => "Rendez-vous ici",
                'thanks'      => "Merci pour la confiance",
                'url'         => url('https://baxe-moneytransfer.com'),
                'contact'     => "merci de contacter "
            ];

        }else{
            // Email de refus de la demaande
            $action = "de refuser";
            $donnees = [
                'email'      => $demande->email,
                'subject'    => 'Invalidation demande',
                'greeting'   => 'Salut '.$demande->prenoms.',',
                'body'       => "Votre demande de compte viens d'être refuser suite ces raisons: \n".$request->raison,
                'urlText'    => "Rendez-vous ici ",
                'thanks'     => "Merci de nous contacter pour plus d'information",
                'url'        => url('https://baxe-moneytransfer.com'),
            ];
        }

            Mail::to($demande->email)->send(new SendMail($donnees));

            // Notification::send($user, new EmailNotification($donnees));

        return redirect()->back()->with('message', "Vous venez ".$action." une demande distributeur.");

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

}
