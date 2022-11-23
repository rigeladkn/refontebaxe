<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Retrait;
use App\Http\Traits\TauxTrait;
use App\Http\Traits\FraisTrait;
use App\Http\Traits\SoldesTrait;
use App\Http\Traits\LocalisationTrait;
use App\Http\Requests\StoreRetraitRequest;
use App\Http\Requests\UpdateRetraitRequest;
use Illuminate\Support\Str;

class RetraitController extends Controller
{
    use SoldesTrait, TauxTrait, LocalisationTrait, FraisTrait;

    public function __construct()
    {
        // $this->middleware('code.confirmation')->only(['create', 'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('shared-pages.retrait.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('shared-pages.retrait.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRetraitRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRetraitRequest $request)
    {
        $client = User::find($request->client_id);

        $montant = $request->client_montant;

        if (!$this->same_country_users(auth()->user(), $client))
        {
            $montant = (int) $this->taux_convert($client->pays->symbole_monnaie, auth()->user()->pays->symbole_monnaie, $montant);
        }

        $commission = $this->frais_get_commission_depot_distributeur(Retrait::class, auth()->user(), $montant);

        $commission = $commission->frais_fixe ?: convertir_un_pourcentage_en_nombre($commission->frais_pourcentage, $montant);

        $taux_to = $this->taux_fetch_one($client->pays->symbole_monnaie, auth()->user()->pays->symbole_monnaie);

        $retrait = Retrait::create([
            'reference'    => Str::random(10),
            'user_id_from' => $client->id,
            'user_id_to'   => auth()->id(),
            'montant'      => $request->client_montant,
            'frais'        => 0,
            'taux_from'    => 1,
            'taux_to'      => $taux_to,
            'pays_from'    => env('APP_ENV') == 'production' ? $this->get_geolocation($client->recent_ip)['country_code2'] : $client->pays->code,
            'pays_to'      => auth()->user()->pays->code,
            'ip_from'      => $client->recent_ip,
            'ip_to'        => env('APP_ENV') == 'production' ? request()->ip() : auth()->user()->recent_ip
        ]);

        if ($retrait)
        {
            $this->set_solde($client, $retrait->id, Retrait::class, $this->new_solde_user_is_from($request->client_montant, $client));

            $this->set_solde(auth()->user(), $retrait->id, Retrait::class, $this->new_solde_user_is_to(auth()->user(), $montant));

            auth()->user()->commissions()->create([
                'operation_type' => Retrait::class,
                'operation_id' => $retrait->id,
                'commission' => $commission
            ]);
        }

        $message = 'Vous venez de faire le retrait de '.format_number_french($montant, 2).' '.auth()->user()->pays->symbole_monnaie.' de '.$client->noms().' via '.env('APP_NAME').'.<br><br> Votre nouveau  solde : '.format_number_french(auth()->user()->soldes->last()->actuel, 2).' '.auth()->user()->pays->symbole_monnaie.'.<br><br>'.env('APP_NAME').' vous remercie pour votre collaboration.';

        /**
         * On retourne le message parce que la redirection se fait en JS apre la requete AJAX, ce qui fait la session flashé ne passe vue qu'il fait la redirection comme si on a cliqué sur un lien
         */
        if ($request->ajax())
        {
            return $message;
        }

        $request->session()->flash('message', $message);

        return redirect()->route('distributeur.retrait.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Retrait  $retrait
     * @return \Illuminate\Http\Response
     */
    public function show(Retrait $retrait)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Retrait  $retrait
     * @return \Illuminate\Http\Response
     */
    public function edit(Retrait $retrait)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRetraitRequest  $request
     * @param  \App\Models\Retrait  $retrait
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRetraitRequest $request, Retrait $retrait)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Retrait  $retrait
     * @return \Illuminate\Http\Response
     */
    public function destroy(Retrait $retrait)
    {
        //
    }
}
