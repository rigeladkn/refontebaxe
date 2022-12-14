<?php

namespace App\Http\Traits;

use App\Models\CommissionModalite;
use App\Models\Frais;
use App\Models\Transfert;
use App\Models\User;

trait FraisTrait
{
    use LocalisationTrait;

    /**
     * frais_get_frais_transfert
     *
     * @param  mixed $operation
     * @param  mixed $user_from
     * @param  mixed $user_to
     * @return object
     */
    public function frais_get_frais_transfert($operation, User $user_from, User $user_to = null)
    {
        
        $all_frais = Frais::get();

        return $all_frais->first(function ($item) use ($user_from, $user_to, $operation) {

            // On check s'ils sont dans le meme pays pour un transfert national ou inter
            $to = $this->same_country_users($user_from, $user_to) ? 'national' : 'international';

            // Si la personne qui envoie a une monnaie d'afrique centrale et si c'est un transfert nati.ou inter.
            if ($user_from->pays->symbole_monnaie == 'XAF' && $item->to == $to && $operation == Transfert::class)
            {
                return $user_from->pays->symbole_monnaie == $item->from;
            }

            // Si la personne qui envoie est en france et si c'est un transfert nati.ou inter.
            if ($user_from->pays->code == 'FR' && $item->to == $to && $operation == Transfert::class)
            {
                return $user_from->pays->code == $item->from;
            }

            // Pour les autres cas s'il sont dans le meme continent et si c'est un transfert nat. ou inter.
            return false !== stripos($item->from, $user_from->pays->continent) && $item->to == $to && $operation == Transfert::class;
        });
    }

    /**
     * Commission du distributeur
     *
     * @param  mixed $operation
     * @param  mixed $distributeur
     * @param  mixed $montant
     * @return object
     */
    public function frais_get_commission_depot_distributeur($operation, User $distributeur, int $montant) // C'est la commission
    {
        $all_commissions = CommissionModalite::get();

        return $all_commissions->first(function ($item) use ($distributeur, $montant, $operation) {
            return false !== stripos($item->continent, $distributeur->pays->continent) && $item->operation == $operation && (($montant >= $item->from) && ($montant <= $item->to));
        });
    }
}
