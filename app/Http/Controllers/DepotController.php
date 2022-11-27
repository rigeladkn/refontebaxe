<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepotRequest;
use App\Http\Requests\UpdateDepotRequest;
use App\Http\Traits\FraisTrait;
use App\Http\Traits\LocalisationTrait;
use App\Http\Traits\SoldesTrait;
use App\Http\Traits\TauxTrait;
use App\Models\Depot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DepotController extends Controller
{
    use SoldesTrait, TauxTrait, LocalisationTrait, FraisTrait;

    public function __construct()
    {
        $this->middleware('code.confirmation')->only(['create', 'store']);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        return view('distributeur.depot.index');
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('distributeur.depot.create');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \App\Http\Requests\StoreDepotRequest  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreDepotRequest $request)
    {
        $client = User::find($request->client_id);

        $taux_to = $this->taux_fetch_one(auth()->user()->pays->symbole_monnaie, $client->pays->symbole_monnaie);

        $montant = $request->montant;

        if (!$this->same_country_users(auth()->user(), $client))
        {
            $montant = $this->taux_convert(auth()->user()->pays->symbole_monnaie, $client->pays->symbole_monnaie, $request->montant);
        }

        $commission = $this->frais_get_commission_depot_distributeur(Depot::class, auth()->user(), $request->montant);

        $commission = $commission->frais_fixe ?: convertir_un_pourcentage_en_nombre($commission->frais_pourcentage, $request->montant);

        $depot = Depot::create([
            'reference'   => Str::random(10),
            'user_id_from' => auth()->user()->id,
            'user_id_to' => $client->id,
            'montant'    => $request->montant,
            'frais'      => 0,
            'taux_from'  => 1,
            'taux_to'    => $taux_to,
            'pays_from'  => env('APP_ENV') == 'production' ? $this->get_geolocation()['country_code2'] : auth()->user()->pays->code,
            'pays_to'    => $client->pays->code,
            'ip_from'    => env('APP_ENV') == 'production' ? request()->ip() : auth()->user()->ip_register,
            'ip_to'      => $client->recent_ip
        ]);

        if ($depot)
        {
            $this->set_solde(auth()->user(), $depot->id, Depot::class, $this->new_solde_user_is_from($request->montant));

            $this->set_solde($client, $depot->id, Depot::class, $this->new_solde_user_is_to($client, $montant));

            auth()->user()->commissions()->create([
                'operation_type' => Depot::class,
                'operation_id' => $depot->id,
                'commission' => $commission
            ]);
        }

        $message = 'Vous venez de faire un dépôt de '.format_number_french($request->montant, 2).' '.auth()->user()->pays->symbole_monnaie.' à '.$client->noms().' via '.env('APP_NAME').'.<br><br> Votre nouveau  solde : '.format_number_french(auth()->user()->soldes->last()->actuel, 2).' '.auth()->user()->pays->symbole_monnaie.'.<br><br>'.env('APP_NAME').' vous remercie pour votre collaboration.';

        /**
         * On retourne le message parce que la redirection se fait en JS apre la requete AJAX, ce qui fait la session flashé ne passe vue qu'il fait la redirection comme si on a cliqué sur un lien
         */
        if ($request->ajax())
        {
            return $message;
        }

        $request->session()->flash('message', $message);

        return redirect()->route('distributeur.depot.index');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Depot  $depot
    * @return \Illuminate\Http\Response
    */
    public function show(Depot $depot)
    {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Depot  $depot
    * @return \Illuminate\Http\Response
    */
    public function edit(Depot $depot)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \App\Http\Requests\UpdateDepotRequest  $request
    * @param  \App\Models\Depot  $depot
    * @return \Illuminate\Http\Response
    */
    public function update(UpdateDepotRequest $request, Depot $depot)
    {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Depot  $depot
    * @return \Illuminate\Http\Response
    */
    public function destroy(Depot $depot)
    {
        //
    }

}
