<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompteBanque extends Model
{
    use HasFactory;

    protected $fillable = [
        'num_compte_bancaire',
        'iban',
        'nom_banque',
        'num_piece_identite',
        'domiciliation',
        'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
