<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Laravel\Passport\Passport;
use Carbon\Carbon;

class AuthServiceProvider extends ServiceProvider
{
    /**
    * The policy mappings for the application.
    *
    * @var array<class-string, class-string>
    */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
    * Register any authentication / authorization services.
    *
    * @return void
    */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();

        Gate::define('is-client', function (User $user) {
            return $user->client;
        });

        Gate::define('is-distributeur', function (User $user) {
            return $user->distributeur;
        });

        Gate::define('is-employe', function (User $user) {
           
            return $user->employe;
        });

        Gate::define('is-admin', function (User $user) {
            if (Gate::allows('is-employe'))
            {
                return $user->employe->departements->first(function ($departement) {
                    return $departement->nom == 'Admin';
                });
            }
            else
            {
                return false;
            }
        });

        Gate::define('is-commercial', function (User $user) {
            if (Gate::allows('is-employe'))
            {
                return $user->employe->departements->first(function ($departement) {
                    return $departement->nom == 'Commercial' || $departement->nom == 'Admin';
                });
            }
            else
            {
                return false;
            }
        });

        Gate::define('is-gestionnaire-compte', function (User $user) {
            if (Gate::allows('is-employe'))
            {
                return $user->employe->departements->first(function ($departement) {
                    return $departement->nom == 'Gestionnaire de compte' || $departement->nom == 'Admin';
                });
            }
            else
            {
                return false;
            }
        });

        Gate::define('is-comptable', function (User $user) {
            if (Gate::allows('is-employe'))
            {
                return $user->employe->departements->first(function ($departement) {
                    return $departement->nom == 'Comptabilité' || $departement->nom == 'Admin';
                });
            }
            else
            {
                return false;
            }
        });

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
            ->greeting(Carbon::now()->format('H') <= 12 ? 'Bonjour,' : 'Bonsoir')
            ->subject("Vérification d'adresse email")
            ->line('Cliquez sur le bouton ci-dessous afin de vérifier votre adresse email.')
            ->action('Je vérifie mon email', $url);
        });
    }
}
