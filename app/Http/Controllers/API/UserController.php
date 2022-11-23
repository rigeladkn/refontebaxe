<?php

namespace App\Http\Controllers\API;

use Auth;
use App\Models\Pays;
use App\Models\User;
use App\Models\Client;
use App\Models\Distributeur;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\LocalisationTrait;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    use LocalisationTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Merci de fournir les donnees pour le cas client
     * @param $nom
     * @param $prenoms
     * @param $code_postal
     * @param $ville
     *
     * Merci de fournir les donnees pour le cas distributeur
     *
     * @param $nom
     * @param $prenoms
     * @param $code_postal
     * @param $ville
     * @param $telephone2 et $telephone3 sont facultatif)
     * @param $activite_principale
     * @param $entreprise_nom
     * @param $path_piece_identite
     * @param $password
     * @param $password_confirmation
     * @param $registre_commerce  (facultatif)
     *
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function update(Request $request)
    {
        // Localisation curent user
        $adress_datas = $this->get_geolocation();

        // recuperation pays user via code pour avoir l'identifiant du pays
        $paysUser = Pays::where('code', $adress_datas['country_code2'])->first();
        $request->merge([
        'telephone2' => $paysUser->indicatif.$request->telephone2,
        'telephone3' => $paysUser->indicatif.$request->telephone3,
        ]);
        $tabValidation = [];

        // Validations
        if(auth()->user()->client()){
            if(isset($request->nom)){
                $tabValidation['nom'] = $request->nom ;
            }
            if(isset($request->prenoms)){
                $tabValidation['prenoms'] = $request->prenoms ;
            }
            if(isset($request->code_postal)){
                $tabValidation['code_postal'] = $request->code_postal ;
            }
            if(isset($request->ville)){
                $tabValidation['ville'] = $request->ville ;
            }
            // Client
            $validator = Validator::make($tabValidation, [
                'nom'         => ['string', 'max:255'],
                'prenoms'     => ['string', 'max:255'],
                'code_postal' => ['string', 'max:255'],
                'ville'       => ['string', 'max:255'],
            ]);

        }else if(auth()->user()->distributeur()){
             // Distributeur
            if(isset($request->nom)){
                $tabValidation['nom'] = $request->nom ;
            }
            if(isset($request->prenoms)){
                $tabValidation['prenoms'] = $request->prenoms ;
            }
            if(isset($request->code_postal)){
                $tabValidation['code_postal'] = $request->code_postal ;
            }
            if(isset($request->ville)){
                $tabValidation['ville'] = $request->ville ;
            }
            if(isset($request->activite_principale)){
                $tabValidation['activite_principale'] = $request->activite_principale ;
            }
            if(isset($request->entreprise_nom)){
                $tabValidation['entreprise_nom'] = $request->entreprise_nom ;
            }
            if(isset($request->communication_baxe)){
                $tabValidation['communication_baxe'] = $request->communication_baxe ;
            }
            if(isset($request->registre_commerce)){
                $tabValidation['registre_commerce'] = $request->registre_commerce ;
            }
            $validator = Validator::make($tabValidation, [
                'nom'                   => ['string', 'max:255'],
                'prenoms'               => ['string', 'max:255'],
                'code_postal'           => ['string', 'max:255'],
                'ville'                 => ['string', 'max:255'],
                'activite_principale'   => ['string', 'max:255'],
                'entreprise_nom'        => ['string', 'max:255'],
                'communication_baxe'    => ['string', 'max:255'],
                'registre_commerce'     => ['string', 'max:255'],
            ]);
        }

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update utilisateur rattaché
        if(auth()->user()->client){
            // Si c'est un client
            auth()->user()->client()->update([
                'nom'         => isset($request->nom) ? ucwords($request->nom) : auth()->user()->client->nom,
                'prenoms'     => isset($request->prenoms) ? ucwords($request->prenoms) : auth()->user()->client->prenoms,
                'code_postal' => isset($request->code_postal) ? $request->code_postal : auth()->user()->client->code_postal,
                'ville'       => isset($request->ville) ? $request->ville : auth()->user()->client->ville,
            ]);
            auth()->user()->refresh();
        }else if(auth()->user()->distributeur){
            // Si c'est un distributeur
            auth()->user()->distributeur()->update([
                'nom'                 => isset($request->nom) ? ucwords($request->nom) : auth()->user()->distributeur->nom,
                'prenoms'             => isset($request->prenoms) ? ucwords($request->prenoms) : auth()->user()->distributeur->prenoms,
                'code_postal'         => isset($request->code_postal) ? $request->code_postal : auth()->user()->distributeur->code_postal,
                'ville'               => isset($request->ville) ? $request->ville : auth()->user()->distributeur->ville,
                'telephone2'          => isset($request->telephone2) ? $request->telephone2 : auth()->user()->distributeur->telephone2,
                'telephone3'          => isset($request->telephone3) ? $request->telephone3 : auth()->user()->distributeur->telephone3,
                'activite_principale' => isset($request->activite_principale) ? $request->activite_principale : auth()->user()->distributeur->activite_principale,
                'registre_commerce'   => isset($request->registre_commerce) ? $request->registre_commerce : auth()->user()->distributeur->registre_commerce,
                'entreprise_nom'      => isset($request->entreprise_nom) ? $request->entreprise_nom : auth()->user()->distributeur->entreprise_nom,
                'communication_baxe'  => isset($request->communication_baxe) ? $request->communication_baxe : auth()->user()->distributeur->communication_baxe,
            ]);
            auth()->user()->refresh();
        }

        $token = auth()->user()->createToken('API Token Login')->plainTextToken;

        auth()->user()->informations = Gate::allows('is-client') ? auth()->user()->client : auth()->user()->distributeur;
        // Return a new JSON
        return response(['user' => auth()->user(), 'pays' => auth()->user()->pays, 'token' => $token]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function verif_password(Request $request)
    {
        $input = $request->only('password');
        $validator = Validator::make($input, [
            'password' => "required|string"
        ]);
        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        // Verification du password
        if (Hash::check($request->password, auth()->user()->password)) {
            $token = auth()->user()->createToken('API Token Login')->plainTextToken;

            $data  = [
                'user'    => auth()->user(),
                'token'   => $token,
            ];
            return response($data, 200);
        }else{
            return response()->json([
                'errors' => [
                    'messag' => "Le mot de passe saisie n'est pas correct"
                ]
            ], 401);
        }

    }


}
