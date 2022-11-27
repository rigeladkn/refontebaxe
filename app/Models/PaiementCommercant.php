<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementCommercant extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $table = 'bt_paiements_commercant';

    public function commercant()
    {
        return $this->belongsTo(User::class, 'user_id_to');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id_from');
    }
}
