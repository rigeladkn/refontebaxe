<?php

namespace App\Http\Controllers\API;

use App\Models\Depot;
use App\Models\Solde;
use App\Models\Retrait;
use App\Http\Traits\SoldesTrait;
use App\Models\CommissionRetire;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StoreSoldeRequest;
use App\Http\Requests\UpdateSoldeRequest;

class SoldeController extends Controller
{
    use SoldesTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $solde = $this->get_solde();

        if($solde == null){
            return response()->json([
                'solde' => 0,
                'commissions' => [], 
                'historique' => [],
            ], 200);
        }

        $transactions = auth()->user()->soldes->sortByDesc('created_at');

        if (request()->limite == "*")
        {
            $limite = $transactions->count();
        }
        elseif (is_numeric(request()->limite))
        {
            $limite = request()->limite;
        }
        else
        {
            $limite = 10;
        }

        $historiques = collect();

        foreach ($transactions as $transaction)
        {
            if ($transaction->operation_type == 'App\Models\Depot')
            {
                $depot = $transaction->depot;

                $acteur = null;

                $montant = $depot->montant;

                if (auth()->id() == $depot->user_id_from) // Montant du distributeur
                {
                    $montant = $transaction->ancien - $transaction->actuel;
                }
                elseif (auth()->id() == $depot->user_id_to) // Montant du client
                {
                    $montant = $transaction->actuel - $transaction->ancien;
                }

                if ($depot->user_id_from == $depot->user_id_to) // Affiche le texte rechargement par carte de crédit
                {
                    $acteur = "Par carte de crédit";

                    $montant = $depot->montant; // On prends le montant du depot
                }
                elseif (auth()->id() == $depot->user_id_from) // Affiche le client pour le distributeur
                {
                    $acteur = 'À '.$depot->user_to->noms();
                }
                elseif (auth()->id() == $depot->user_id_to) // Affiche le distributeur pour le client
                {
                    $acteur = 'Chez '.$depot->user_from->distributeur->entreprise_nom;
                }
                else {}

                $historiques->push([
                    'type'       => 'depot',
                    'user_from'  => $depot->user_id_from,
                    'user_to'    => $depot->user_id_to,
                    'created_at' => $depot->created_at->format('d-m-Y à H:i'),
                    'user'       => $acteur,
                    'frais'      => $depot->frais,
                    'montant'    => $montant,
                    'total'      => $montant + $depot->frais,
                ]);
            }
            elseif ($transaction->operation_type == 'App\Models\Retrait')
            {
                $retrait = $transaction->retrait;

                $montant = $retrait->montant;

                if (auth()->id() == $retrait->user_id_from)
                {
                    $acteur = 'Chez '.$retrait->distributeur->distributeur->entreprise_nom;
                }
                elseif (auth()->id() == $retrait->user_id_to)
                {
                    $acteur = 'De '.$retrait->client->noms();
                }
                else {}

                if (auth()->id() == $retrait->user_id_from) // Le montant du client
                {
                    $montant = $transaction->ancien - $transaction->actuel;
                }
                elseif (auth()->id() == $retrait->user_id_to) // Le montant du distributeur
                {
                    $montant = $transaction->actuel - $transaction->ancien;
                }
                else {}

                $historiques->push([
                    'type'       => 'retrait',
                    'user_from'  => $retrait->user_id_from,
                    'user_to'    => $retrait->user_id_to,
                    'created_at' => $retrait->created_at->format('d-m-Y à H:i'),
                    'user'       => $acteur,
                    'frais'      => $retrait->frais,
                    'montant'    => $montant,
                    'total'      => $montant + $retrait->frais,
                ]);
            }
            elseif ($transaction->operation_type == 'App\Models\Transfert')
            {
                $transfert = $transaction->transfert;

                $montant = $transfert->montant;

                if (auth()->id() == $transfert->user_id_from) // Le nom du destinataire
                {
                    $acteur = 'À '.$transfert->user_to->noms();
                }
                elseif (auth()->id() == $transfert->user_id_to) // Le nom de l'expediteur
                {
                    $acteur = 'De '.$transfert->user_from->noms();
                }

                if (auth()->id() == $transfert->user_id_from) // Le montant de l'expediteur
                {
                    $montant = $transfert->montant;

                    $total = $montant + $transfert->frais;
                }
                elseif (auth()->id() == $transfert->user_id_to) // Le montant du destinataire
                {
                    $montant = $transaction->actuel - $transaction->ancien;

                    $total = $montant;
                }
                else {}

                $historiques->push([
                    'type'       => 'transfert',
                    'user_from'  => $transfert->user_id_from,

                    'created_at' => $transfert->created_at->format('d-m-Y à H:i'),
                    'user'       => $acteur,
                    'frais'      => auth()->id() == $transfert->user_id_from ? format_number_french($transfert->frais, 2) : '--',
                    'montant'    => $montant,
                    'total'      => $total,
                ]);
            }
            elseif ($transaction->operation_type == 'App\Models\PaiementCommercant')
            {
                $paiementCommercant = $transaction->paiement_commercant;
                $montant = 0;
                $acteur = '';
                $frais = 0;

                if (auth()->id() == $paiementCommercant->user_id_from) 
                {
                    $acteur = 'Au commercant '.$paiementCommercant->commercant->noms();
                    $montant = $paiementCommercant->montant;
                }
                elseif (auth()->id() == $paiementCommercant->user_id_to) 
                {
                    $acteur = 'Du client '.$paiementCommercant->commercant->noms();
                    $montant = $transaction->actuel - $transaction->ancien;
                    $frais = $paiementCommercant->frais; //convert this to the shopkeeper change when payment can be made between different countries
                }

                $total = $montant;

                $historiques->push([
                    'type'       => 'transfert',
                    'user_from'  => $paiementCommercant->client->noms(),
                    'user_to'    => $paiementCommercant->commercant->noms(),
                    'created_at' => $paiementCommercant->created_at->format('d-m-Y à H:i'),
                    'user'       => $acteur,
                    'frais'      => $frais, 
                    'montant'    => $montant,
                    'icon'       => 'fas fa-paper-plane text-primary fs-4',
                    'total'      => $total,
                ]);
            }
        }
        
        $transactions = $historiques;

        $transactions = create_pagination_with_collection($transactions, $limite);

        $transactions->withPath('solde');

        $commission_depot = null;
        $commission_retrait = null;
        $commission_total = null;

        if (Gate::allows('is-distributeur'))
        {
            $commission_depot = auth()->user()->commissions->where('operation_type', Depot::class)->where('statut', false)->sum('commission');

            $commission_reste_retirer = auth()->user()->commissions->where('operation_type', CommissionRetire::class)->where('statut', false)->sum('commission');

            $commission_retrait = auth()->user()->commissions->where('operation_type', Retrait::class)->where('statut', false)->sum('commission');

            $commission_total = $commission_depot + $commission_retrait + $commission_reste_retirer;
        }
        $solde->ancien = round_somme($solde->ancien);
        $solde->actuel = round_somme($solde->actuel);
        return response()->json([
            'solde' => $solde,
            'commissions' => [
                'depot' => $commission_depot,
                'retrait' => $commission_retrait,
                'total' => $commission_total,
            ],
            'historique' => $transactions,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSoldeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSoldeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Solde  $solde
     * @return \Illuminate\Http\Response
     */
    public function show(Solde $solde)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSoldeRequest  $request
     * @param  \App\Models\Solde  $solde
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSoldeRequest $request, Solde $solde)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Solde  $solde
     * @return \Illuminate\Http\Response
     */
    public function destroy(Solde $solde)
    {
        //
    }
}
