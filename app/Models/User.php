<?php

namespace App\Models;

use App\Models\Employe;
use App\Models\Virement;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = ['id'];

    /**
    * The attributes that should be hidden for serialization.
    *
    * @var array<int, string>
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
    * The attributes that should be cast.
    *
    * @var array<string, string>
    */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
    * noms
    *
    * @return void
    */
    public function noms()
    {
        if ($this->client)
        {
            return $this->client->nom.' '.$this->client->prenoms;
        }
        elseif ($this->distributeur)
        {
            return $this->distributeur->nom.' '.$this->distributeur->prenoms;
        }
        elseif($this->commercant)
        {
            return $this->commercant->nom.' '.$this->commercant->prenoms;
        }
        elseif ($this->employe)
        {
            return $this->employe->nom.' '.$this->employe->prenoms;
        }
    }

    /**
    * Retourne les infos de l'utilisateur
    *
    * @return void
    */
    public function client()
    {
        return $this->hasOne(Client::class);
    }

    /**
    * Retourne les infos du distributeur
    *
    * @return void
    */
    public function distributeur()
    {
        return $this->hasOne(Distributeur::class);
    }

    public function commercant()
    {
        return $this->hasOne(Commercant::class);
    }

    public function pays()
    {
        return $this->belongsTo(Pays::class, 'pays_register_id');
    }

    /**
    * soldes
    *
    * @return void
    */
    public function soldes()
    {
        return $this->hasMany(Solde::class);
    }

    /**
    * cartes
    *
    * @return void
    */
    public function cartes_credits()
    {
        return $this->hasMany(CarteCredit::class);
    }

    /**
    * commissions
    *
    * @return void
    */
    public function commissions()
    {
        return $this->hasMany(Commission::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $baseurl =url('/');
        $url = $baseurl.'/reset-password/token='.$token;
        return $this->notify(new ResetPasswordNotification($url));
    }

    /**
    * compte_bancaires
    *
    * @return void
    */
    public function compte_bancaires()
    {
        return $this->hasMany(CompteBanque::class);
    }

    public function employe()
    {
        return $this->hasOne(Employe::class);
    }

    public function virements()
    {
        return $this->hasMany(Virement::class);
    }
}
