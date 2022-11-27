<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeDistributeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'pays_register_id',
        'nom',
        'prenoms',
        'code_postal',
        'ville',
        'email',
        'telephone',
        'telephone2',
        'telephone3',
        'activite_principale',
        'entreprise_nom',
        'registre_commerce',
        'path_piece_identite',
        'path_media_du_local',
        'communication_baxe',
        'pays_register_id',
        'ip_register',
        'recent_ip'
    ];

}
