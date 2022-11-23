<?php

namespace App\Models;

use App\Models\Retrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solde extends Model
{
    use HasFactory;

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];
    protected $table = 'soldes';

    /**
    * user
    *
    * @return void
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transfert()
    {
        return $this->belongsTo(Transfert::class, 'operation_id');
    }

    public function retrait()
    {
        return $this->belongsTo(Retrait::class, 'operation_id');
    }

    public function depot()
    {
        return $this->belongsTo(Depot::class, 'operation_id');
    }

    public function rechargement()
    {
        return $this->belongsTo(Rechargement::class, 'operation_id');
    }

    public function paiement_commercant()
    {
        return $this->belongsTo(PaiementCommercant::class, 'operation_id');
    }
}
