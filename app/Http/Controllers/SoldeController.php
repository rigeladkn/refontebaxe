<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rechargement;
use App\Models\Solde;
use App\Http\Traits\SoldesTrait;
use App\Http\Traits\TauxTrait;
use App\Models\Distributeur;
use Illuminate\Support\Str;


class SoldeController extends Controller
{
    use SoldesTrait, TauxTrait;

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
        $distributeur = Distributeur::find($request->distributeur_id);

        $request->validate([
            'montantRecharge' => ['required', 'numeric', 'min:1', 'max:99999999'],
        ]);
        // Convertion du monnais saisie en euros pour les controls Je prend toujour Euro comme monnais de base
        $montantConvert_EUR = $this->taux_convert(auth()->user()->pays->symbole_monnaie, 'EUR', $request->montantRecharge);

        if($this->cumule_rechargement_jour($distributeur) > 0){
            $totalRechargeJourConvert_EUR = $this->taux_convert(auth()->user()->pays->symbole_monnaie, 'EUR', $this->cumule_rechargement_jour($distributeur));
        }else{
            $totalRechargeJourConvert_EUR = 0;
        }
        // Montant Globale de recharge par jour doit etre au minimum 999 et au max 5700 €
        if(($totalRechargeJourConvert_EUR +  $montantConvert_EUR) < 999){
            return redirect()->back()->withErrors(["Désolé ! la somme minimale de rechargement pérmis est :".$this->taux_convert('EUR', auth()->user()->pays->symbole_monnaie, 999)." ".auth()->user()->pays->symbole_monnaie]);

        }else if(($totalRechargeJourConvert_EUR +  $montantConvert_EUR) > 5700){

                return redirect()->back()->withErrors(["Désolé ! la somme maximale de rechargement pérmis par jour qui est :".$this->taux_convert('EUR', auth()->user()->pays->symbole_monnaie, 5700)." ".auth()->user()->pays->symbole_monnaie]);
        }else{
            // On passe maintenent à la rechergement du solde distributeur

            $rechargement = Rechargement::create([
                'reference'         => Str::random(10),
                'user_id'           => auth()->user()->id,
                'distributeur_id'   => $distributeur->id,
                'montant'           => $request->montantRecharge,
            ]);

            if($rechargement){

                $this->set_solde($distributeur->user, $rechargement->id, Rechargement::class, $this->new_solde_user_is_to($distributeur->user, $request->montantRecharge));

            }

            return redirect()->back()->with('message', "Le compte de ce distributeur est rechargé de : $request->montantRecharge ".auth()->user()->pays->symbole_monnaie);
        }

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
}
