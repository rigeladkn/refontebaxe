<?php

namespace App\Http\Traits;

use App\Models\User;
use App\Models\Solde;
use App\Models\Rechargement;
use App\Models\Distributeur;
trait SoldesTrait
{
    /**
    * get_solde
    *
    * @param  mixed $user
    * @return object
    */
    public function get_solde(User $user = null)
    {
        return $user ? $user->soldes->last() : auth()->user()->soldes->last();
    }

    /**
    * set_solde
    *
    * @param  mixed $user
    * @param  mixed $operation_id
    * @param  mixed $operation_type
    * @param  mixed $new_solde
    * @return void
    */
    public function set_solde(User $user, $operation_id, $operation_type, $new_solde)
    {
        $user->soldes()->create([
            'operation_id'   => $operation_id,
            'operation_type' => $operation_type,
            'ancien'         => $user->soldes->last() ? $user->soldes->last()->actuel : 0,
            'actuel'         => $new_solde,
        ]);

        $user->refresh();
    }

    /**
     * Check par rapport à un montant s'il a le solde suffisant
     *
     * @param  mixed $montant
     * @return bool
     */
    public function not_required_solde($montant, User $user = null)
    {
        return $montant > ($this->get_solde($user ?? null) ? $this->get_solde($user ?? null)->actuel : 0);
    }

    /**
     * Lorsque c'est l'user qui envoie envoie l'argent et que c'est lui qu'on doit reduire son solde
     *
     * @param  mixed $montant
     * @return double
     */
    public function new_solde_user_is_from($montant, User $user = null)
    {
        return $this->get_solde($user ?? null)->actuel - $montant;
    }

    /**
     * Lorsque c'est l'user qui recoit l'argent.
     *
     * @param  \App\Models\User $user
     * @param  double $montant
     * @return double
     */
    public function new_solde_user_is_to(User $user, $montant)
    {
        return $this->get_solde($user) ? ($this->get_solde($user)->actuel + $montant) : $montant;
    }

    /**
     * Cette method nous retourne les rechargement du jour de ce distributeur
     *
     * @param  \App\Models\Distributeur $distributeur
     * @return double
     */
    public function cumule_rechargement_jour(Distributeur $distributeur)
    {
        return Rechargement::whereDate('created_at', now())->where('distributeur_id', $distributeur->id)->sum('montant');
    }

}
