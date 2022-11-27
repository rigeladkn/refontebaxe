<?php

namespace App\Repositories;

use App\Models\Solde;
use Illuminate\Support\Str;
use Illuminate\Http\Request;


class SoldeRepository
{

    public function getHistories($user, $path_pagination)
    {
        $transactions = $user->soldes()->orderBy('created_at', 'desc')->paginate(10);
        // $transactions = Solde::where('user_id', $user->id)->get();
        $historiques = collect();

        foreach ($transactions as $transaction)
        {

            $acteur = null;
            $total = null;
            $icon = null;
            if ($transaction->operation_type == 'App\Models\Depot')
            {
                $depot = $transaction->depot;



                $montant = $depot->montant;

                if (auth()->id() == $depot->user_id_from) // Montant du distributeur
                {
                    $montant = $transaction->ancien - $transaction->actuel;

                    $icon = "fas fa-arrow-up text-primary fs-4";
                }
                elseif (auth()->id() == $depot->user_id_to) // Montant du client
                {
                    $montant = $transaction->actuel - $transaction->ancien;

                    $icon = "fas fa-arrow-down text-success fs-4";
                }
                else {}

                if ($depot->user_id_from == $depot->user_id_to) // Affiche le texte rechargement par carte de crédit
                {
                    $acteur = "Par carte de crédit";

                    $montant = $depot->montant; // On prends le montant du depot

                    $icon = "fas fa-arrow-down text-success fs-4";
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
                    'icon'       => $icon,
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

                    $icon = "fas fa-arrow-up text-danger fs-4";
                }
                elseif (auth()->id() == $retrait->user_id_to)
                {
                    $acteur = 'De '.$retrait->client->noms();

                    $icon = "fas fa-arrow-down text-pink fs-4";
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
                    'icon'       => $icon,
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
                    'user_to'    => $transfert->user_id_to,
                    'created_at' => $transfert->created_at->format('d-m-Y à H:i'),
                    'user'       => $acteur,
                    'frais'      => auth()->id() == $transfert->user_id_from ? format_number_french($transfert->frais, 2) : '--',
                    'montant'    => $montant,
                    'icon'       => 'fas fa-paper-plane text-primary fs-4',
                    'total'      => $total,
                ]);
            } 
            elseif ($transaction->operation_type == 'App\Models\Rechargement')
            {
                $rechargement = $transaction->rechargement;
                $distributeur =  $rechargement->distributeur;
                $montant = $rechargement->montant;
                $acteur = 'Distributeur: '.$distributeur->nom.' '. $distributeur->prenoms;
               
                $historiques->push([
                    'type'       => 'transfert',
                    'user_from'  => '',
                    'user_to'    => '',
                    'created_at' => $rechargement->created_at->format('d-m-Y à H:i'),
                    'user'       => $acteur,
                    'frais'      => 0,
                    'montant'    => $montant,
                    'icon'       => 'fas fa-plus text-primary fs-4',
                    'total'      => $total,
                ]);
            }
            elseif ($transaction->operation_type == 'App\Models\PaiementCommercant')
            {
                $paiementCommercant = $transaction->paiement_commercant; 
                $montant = $paiementCommercant->montant;
                $acteur = 'Au commercant '.$paiementCommercant->commercant->noms();
                $total = $montant;

                $historiques->push([
                    'type'       => 'transfert',
                    'user_from'  => $paiementCommercant->client->noms(),
                    'user_to'    => $paiementCommercant->commercant->noms(),
                    'created_at' => $paiementCommercant->created_at->format('d-m-Y à H:i'),
                    'user'       => $acteur,
                    'frais'      => 0, //because fees are taken from the shopkeeper, not the customer
                    'montant'    => $montant,
                    'icon'       => 'fas fa-paper-plane text-primary fs-4',
                    'total'      => $total,
                ]);
            }
        }

        $transactionsContent = $historiques;

        return ['transactions' => $transactions, 'transactionsContent' => $transactionsContent];
    }
}