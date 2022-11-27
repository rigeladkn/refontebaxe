<?php

namespace App\Models;

use App\Models\User;
use App\Models\CompteBanque;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Virement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function compte_bancaire()
    {
        return $this->belongsTo(CompteBanque::class, 'compte_banque_id');
    }

    public function getStatutAttribute($value)
    {
        if ($value === null)
        {
            return 'Lancé';
        }
        elseif ($value === 1)
        {
            return 'Effectué';
        }
        elseif ($value === 0)
        {
            return 'Refusé';
        }
    }

    public function initiateur()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
