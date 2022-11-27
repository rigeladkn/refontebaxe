<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Models\User;
use App\Models\Employe;
use App\Models\Departement;
use App\Models\Distributeur;
use App\Notifications\EmployeCree;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function employe()
    {
        $employes = Employe::/* where('user_id', '<>', auth()->id())-> */orderBy('nom')->orderBy('prenoms')->paginate(20);

        return view('admin.employe.index', compact('employes'));
    }

    public function employe_create()
    {
        $pays = Pays::orderBy('nom')->get();

        $departements = Departement::orderBy('nom')->get();

        $situations_matrimoniales = [
            'Célibataire',
            'Marié(e)',
            'Divorcé(e)',
            'Veuf(ve)',
        ];

        $genres = [
            'Masculin',
            'Féminin',
        ];

        return view('admin.employe.create', compact('pays', 'departements', 'situations_matrimoniales', 'genres'));
    }

    public function employe_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom'                    => ['required', 'string', 'max:255'],
            'prenoms'                => ['required', 'string', 'max:255'],
            'genre'                  => ['required', 'string', 'max:255', 'in:Masculin,Féminin'],
            'situation_matrimoniale' => ['required', 'string', 'max:255', 'in:Célibataire,Marié(e),Divorcé(e),Veuf(ve)'],
            'email'                  => ['required', 'string', 'email', 'max:255', 'unique:employes,email', 'unique:users,email'],
            'pays'                   => ['required', 'integer', 'exists:pays,id'],
            'ville'                  => ['required', 'string', 'max:255'],
            'adresse'                => ['required', 'string', 'max:255'],
            'departement'            => ['required', 'integer', 'exists:departements,id'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $pays = Pays::find($request->pays);

            $telephone = $pays->indicatif.$request->telephone;

            if (str_starts_with($request->telephone, '00') || str_starts_with($request->telephone, '+'))
            {
                $validator->errors()->add('telephone', "La valeur du champ doit être saisi sans l'indicatif du pays.");
            }

            if (User::where('telephone', $telephone)->exists() || Employe::where('telephone', $telephone)->exists())
            {
                $validator->errors()->add('telephone', 'La valeur du champ est déjà utilisée.');
            }
        });

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $mot_de_passe = Str::random(10);

        $pays = Pays::find($request->pays);

        if ($request->creation_acces)
        {
            $user = User::create([
                'pays_register_id' => $request->pays,
                'ip_register'      => '127.0.0.1',
                'email'            => strtolower($request->email),
                'telephone'        => str_replace(' ', '', $pays->indicatif.$request->telephone),
                'recent_ip'        => '127.0.0.1',
                'password'         => Hash::make($mot_de_passe),
            ]);

            $user->mot_de_passe = $mot_de_passe;

            $user->notify(new EmployeCree($user));
        }

        $employe = Employe::create([
            'user_id'                => isset($user) ? $user->id : null,
            'pays_id'                => $request->pays,
            'nom'                    => ucfirst(strtolower($request->nom)),
            'prenoms'                => ucfirst(strtolower($request->prenoms)),
            'genre'                  => ucfirst(strtolower($request->genre)),
            'situation_matrimoniale' => ucfirst(strtolower($request->situation_matrimoniale)),
            'telephone'              => str_replace(' ', '', $pays->indicatif.$request->telephone),
            'email'                  => strtolower($request->email),
            'ville'                  => ucfirst(strtolower($request->ville)),
            'adresse'                => ucfirst(strtolower($request->adresse)),
        ]);

        $employe->departements()->attach($request->departement);

        return redirect()->route('admin.employe.index')->with('message', 'Employé créé avec succès.');
    }

    public function set_droit_distributeur(User $user)
    {
        Distributeur::create([
            'reference'             => Str::random(8),
            'user_id'               => $user->id,
            'pays_id'               => $user->pays_register_id,
            'nom'                   => ucfirst(strtolower($user->employe->nom)),
            'prenoms'               => ucfirst(strtolower($user->employe->prenoms)),
            'code_postal'           => rand(10000, 99999),
            'ville'                 => ucfirst(strtolower($user->employe->ville)),
            'email'                 => strtolower($user->email),
            'telephone'             => str_replace(' ', '', $user->employe->telephone),
            'activite_principale'   => 'Gestionnaire de compte chez Baxe',
            'entreprise_nom'        => 'Baxe',
            'path_piece_identitite' => json_encode([]),
            'communication_baxe'    => 'Interne'
        ]);

        return redirect()->route('admin.employe.index')->with('message', "Le gestionnaire de compte peut maintenant effectuer des opérations de distributeur.");
    }
}
