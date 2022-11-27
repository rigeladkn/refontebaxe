<?php

namespace App\Models;

use App\Models\Pays;
use App\Models\Departement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employe extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function departements()
    {
        return $this->belongsToMany(Departement::class, 'departement_employe')->withPivot('poste', 'niveau', 'created_at', 'updated_at');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class);
    }
}
