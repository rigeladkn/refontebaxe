<?php

namespace App\Http\Controllers;

use App\Models\Rechargement;
use App\Http\Traits\SoldesTrait;
use App\Models\MoyenRechargement;
use App\Http\Requests\UpdateRechargementRequest;
use App\Http\Traits\LocalisationTrait;
use App\Models\Depot;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RechargementController extends Controller
{
    use SoldesTrait, LocalisationTrait;

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $liste_moyen = MoyenRechargement::get();

        foreach ($liste_moyen as $moyen)
        {
            $moyen->statut = true;

            if ($moyen->slug == 'carte-de-crédit' && auth()->user()->pays->continent == 'Africa')
            {
                $moyen->statut = false;
            }
        }

        return view('client.rechargement.index', compact('liste_moyen'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create(MoyenRechargement $moyenRechargement)
    {
        $moyenRechargement->statut = true;

        /**
        * TODO affiché que c'est 2% du montant qui vas etre debiter sur son compte
        */

        if ($moyenRechargement->slug == 'carte-de-crédit' && auth()->user()->pays->continent == 'Africa')
        {
            $moyenRechargement->statut = false;
        }

        if ($moyenRechargement->slug == 'banque')
        {
            $moyenRechargement->statut = false;
        }
        
        return view('client.rechargement.create', compact('moyenRechargement'));
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \App\Http\Requests\StoreRechargementRequest  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request, MoyenRechargement $moyenRechargement)
    {
        $request->validate([
            /**
            * TODO le montant minimun est de 10
            */
            'montant' => ['required', 'integer', 'min:10', 'max:99999999'],
        ]);

        if (auth()->user()->pays->continent == 'Africa')
        {
            abort(403);
        }

        $message = '';

        if ($moyenRechargement->slug == 'carte-de-crédit')
        {
            try
            {
                $frais_suppelementaire = convertir_un_pourcentage_en_nombre(2, $request->montant);

                $frais = $frais_suppelementaire;

                $montant = $request->montant;

                if (auth()->user()->pays->continent != 'Africa')
                {
                    $montant = $request->montant * 100;

                    $frais_suppelementaire = ($frais_suppelementaire * 100);
                }

                $montant = $montant + $frais_suppelementaire;

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
                        'reference'   => Str::random(10),
                        'user_id_from' => auth()->user()->id,
                        'user_id_to' => auth()->user()->id,
                        'montant'    => $request->montant,
                        'frais'      => $frais,
                        'taux_from'  => 1,
                        'taux_to'    => 1,
                        'pays_from'  => env('APP_ENV') == 'production' ? $this->get_geolocation()['country_code2'] : auth()->user()->pays->code,
                        'pays_to'    => auth()->user()->pays->code,
                        'ip_from'    => env('APP_ENV') == 'production' ? request()->ip() : auth()->user()->ip_register,
                        'ip_to'      => auth()->user()->recent_ip
                    ]);

                    $this->set_solde(auth()->user(), $depot->id, Depot::class, $this->new_solde_user_is_to(auth()->user(), $request->montant));

                    $message = 'Vous venez de recharger votre compte de '.format_number_french($request->montant, 2).' '.auth()->user()->pays->monnaie.'.<br><br> Votre nouveau solde : '.format_number_french(auth()->user()->soldes->last()->actuel, 2).' '.auth()->user()->pays->monnaie.'.<br><br>'.env('APP_NAME').' vous remercie pour votre fidélité.';
                }
            }
            catch (\Throwable $th)
            {
                $message = "Le rechargement a échoué. <br><br>Veuillez réessayer plus tard.";
            }
        }

    
        $request->session()->flash('message', $message);

        return redirect()->route('client.rechargement.index');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Rechargement  $rechargement
    * @return \Illuminate\Http\Response
    */
    public function show(Rechargement $rechargement)
    {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Rechargement  $rechargement
    * @return \Illuminate\Http\Response
    */
    public function edit(Rechargement $rechargement)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \App\Http\Requests\UpdateRechargementRequest  $request
    * @param  \App\Models\Rechargement  $rechargement
    * @return \Illuminate\Http\Response
    */
    public function update(UpdateRechargementRequest $request, Rechargement $rechargement)
    {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Rechargement  $rechargement
    * @return \Illuminate\Http\Response
    */
    public function destroy(Rechargement $rechargement)
    {
        //
    }


}
