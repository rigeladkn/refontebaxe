<?php

namespace App\Models;

use App\Models\Rechargement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Distributeur extends Model
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

    /**
     * depots
     *
     * @return void
     */
    public function depots()
    {
        return $this->hasManyThrough(Depot::class, User::class, 'id', 'user_id_from', 'user_id');
    }

    /**
     * distributeur_retraits
     *
     * @return void
     */
    public function retraits()
    {
        return $this->hasManyThrough(Retrait::class, User::class, 'id', 'user_id_to', 'user_id');
    }

    public function rechargements()
    {
        return $this->hasMany(Rechargement::class);
    }

    public function findClientData(){
        return Client::where('user_id', $this->id)->first();
    }
}
