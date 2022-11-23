<?php

namespace App\Http\Controllers;

use App\Models\Depot;
use App\Models\Retrait;
use App\Models\Distributeur;
use Illuminate\Http\Request;
use App\Http\Traits\SoldesTrait;
use App\Models\CommissionRetire;
use App\Services\ClientQrService;
use App\Services\DistributeurService;
use Illuminate\Support\Facades\Validator;


class DistributeurController extends Controller
{
    use SoldesTrait;

    private $distributeurService;
    private $clientQrService;

    public function __construct(DistributeurService $distributeurService, ClientQrService $clientQrService)
    {
        $this->distributeurService = $distributeurService;
        $this->clientQrService = $clientQrService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $distributeurs = Distributeur::paginate(20);
        return view('auth.list-distributeur')->with('distributeurs', $distributeurs);
    }
    public function scanQrPage(){
        return view ('distributeur.scan-qr');
    }

    public function currentCustomerDetails(Request $request){
        $currentClient =  $request->get("distributeurCurrentClient");
        $id = ($currentClient == null) ? null : $currentClient->id;
        $currentClientCredentials = $this->clientQrService->findClientCredentialsById($id);
       
        return view('distributeur.current-customer')->with("currentClientCredentials", $currentClientCredentials);


    }
    public function map(Request $request){
        $distributeurs = $this->distributeurService->findDistributeurs($request);
        $data = array();
        $data['distributeurs'] = $distributeurs;
        return view('distributeur.map', $data);
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Distributeur $distributeur)
    {
        $solde = $this->get_solde($distributeur->user)->actuel ?? 0;

        $commission_depot = $distributeur->user->commissions->where('operation_type', Depot::class)->where('statut', false)->sum('commission');

        $commission_reste_retirer = $distributeur->user->commissions->where('operation_type', CommissionRetire::class)->where('statut', false)->sum('commission');

        $commission_retrait = $distributeur->user->commissions->where('operation_type', Retrait::class)->where('statut', false)->sum('commission');

        $commission_total = ($commission_depot + $commission_retrait + $commission_reste_retirer);

        return view('auth.affiche-distributeur')->with('distributeur', $distributeur)
                ->with('solde', $solde)
                ->with('commission_retrait', $commission_retrait)
                ->with('commission_depot', $commission_depot)
                ->with('commission_total', $commission_total);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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

    public function retrait_solde(Request $request, Distributeur $distributeur)
    {
        $commission_depot = $distributeur->user->commissions->where('operation_type', Depot::class)->where('statut', false)->sum('commission');

        $commission_reste_retirer = $distributeur->user->commissions->where('operation_type', CommissionRetire::class)->where('statut', false)->sum('commission');

        $commission_retrait = $distributeur->user->commissions->where('operation_type', Retrait::class)->where('statut', false)->sum('commission');

        $commission_total = ($commission_depot + $commission_retrait + $commission_reste_retirer);

        $validator = Validator::make($request->all(), [
            'montant' => ['required', 'numeric', 'min:1', 'max:'.$commission_total],
        ]);

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $distributeur->user->commissions()->update([
            'statut' => true,
        ]);

        $commission_retire = CommissionRetire::create([
            'employe_id'      => auth()->user()->employe->id,
            'distributeur_id' => $distributeur->id,
            'montant'         => $request->montant,
        ]);

        if ($request->montant < $commission_total)
        {
            $distributeur->user->commissions()->create([
                'operation_type' => CommissionRetire::class,
                'operation_id'   => $commission_retire->id,
                'commission'     => $commission_total - $request->montant,
            ]);
        }

        return redirect()->back()->with('message', 'Commissions du distributeur payées.');
    }
}
