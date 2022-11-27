<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Distributeur;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;


class UpdateUserController extends Controller
{
    public function update(Request $request){

        $input = $request->only('password');
        $validator = Validator::make($input, [
            'password' => "required|string"
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['error' => "Entrer votre mot de passe pour éffectuer la modification"])->withInput();
        }
        // Verification du password
        if (Hash::check($request->password, auth()->user()->password)) {

            if(auth()->user()->client()){
                
                $validator = Validator::make($request->all(), [
                    'nom'         => ['required', 'string', 'max:255'],
                    'prenoms'     => ['required', 'string', 'max:255'],
                    'code_postal' => ['required', 'string', 'max:255'],
                    'ville'       => ['required', 'string', 'max:255'],
                ]);

            }else if(auth()->user()->distributeur()){
                
                $validator = Validator::make($request->all(), [
                    'nom'                   => ['required', 'string', 'max:255'],
                    'prenoms'               => ['required', 'string', 'max:255'],
                    'code_postal'           => ['required', 'string', 'max:255'],
                    'ville'                 => ['required', 'string', 'max:255'],
                    'entreprise_nom'        => ['required', 'string', 'max:255'],
                    'registre_commerce'     => ['required', 'string', 'max:255'],
                    'communication_baxe'    => ['required', 'string', 'max:255'],
                ]);
            }
            if ($validator->fails()){
                return redirect()->back()->withErrors($validator)->withInput();
            }

            if(auth()->user()->client){
                // Si c'est un client
                auth()->user()->client()->update([
                    'nom'         => isset($request->nom) ? $request->nom : auth()->user()->client->nom,
                    'prenoms'     => isset($request->prenoms) ? $request->prenoms : auth()->user()->client->prenoms,
                    'code_postal' => isset($request->code_postal) ? $request->code_postal : auth()->user()->client->code_postal,
                    'ville'       => isset($request->ville) ? $request->ville : auth()->user()->client->ville,
                ]);
                auth()->user()->refresh();
                $infos = auth()->user()->client;

            }else if(auth()->user()->distributeur){
                // Si c'est un distributeur
                auth()->user()->distributeur()->update([
                    'nom'                 => isset($request->nom) ? $request->nom : auth()->user()->distributeur->nom,
                    'prenoms'             => isset($request->prenoms) ? $request->prenoms : auth()->user()->distributeur->prenoms,
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
                $infos = auth()->user()->distributeur;
            }
            $userConnect = auth()->user();
            $comptes = $userConnect->compte_bancaires;
            
            return view('auth.profil-utilisateur', compact('userConnect', 'infos', 'comptes'))->with('message', 'Profile mis à jour avec succès.');
        
        }else{

            return redirect()->back()->withErrors(['error' => "Désolé ! le mot de passe saisi est incorrect"])->withInput();
        }
    
    }


}


