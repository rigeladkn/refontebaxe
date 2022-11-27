<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];

    /**
     * noms
     *
     * @return string
     */
    public function noms()
    {
        return $this->nom.' '.$this->prenoms;
    }

    /**
     * Retourne l'utilisateur
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }

    public function transferts_from()
    {
        return $this->hasManyThrough(Transfert::class, User::class, 'id', 'user_id_from', 'user_id');
    }

    public function transferts_to()
    {
        return $this->hasManyThrough(Transfert::class, User::class, 'id', 'user_id_to', 'user_id');
    }

    /**
     * client_retraits
     *
     * @return void
     */
    public function retraits()
    {
        return $this->hasManyThrough(Retrait::class, User::class, 'id', 'user_id_from', 'user_id');
    }
}
