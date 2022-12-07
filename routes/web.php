<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\TransfertController;
use App\Http\Controllers\AuthenticationController;

require __DIR__.'/auth.php';

if (env('APP_ENV') == 'production') {
    URL::forceScheme('https');
}

Route::get('/', [WelcomeController::class, 'index']);
Route::get('/contact', [WelcomeController::class, 'contact'])->name('contact');
Route::get('/about', [WelcomeController::class, 'about'])->name('about');
Route::get('/signup', [WelcomeController::class, 'signup'])->name('signup');
Route::get('/login', [WelcomeController::class, 'login'])->name('login');
Route::post('/login', [AuthenticationController::class, 'login']);
//


//
Route::get('/api/validation/{codeDetails}', [AuthenticationController::class, 'validateCode']);

Route::middleware(['auth', 'verified', 'ip.valid'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/transactions', [HomeController::class, 'transactions'])->name('transactions');
    
    //deposit
    Route::get('/deposit', [HomeController::class, 'deposit'])->name('deposit');

    /**
    * * Route concernant les clients
    */
    Route::middleware(['can:is-client'])->prefix('client')->name('client.')->group(function () {

        Route::get('paiement-commercant', [PaiementCommercantController::class, 'formPaiement'])->name('paiement-commercant.form-paiement');

        // Transfert ou send routes
        Route::prefix('transfert')->name('transfert.')->group(function () {

            Route::get('/send', [TransfertController::class, 'index'])->name('send');
            Route::get('/send-confirm', [TransfertController::class, 'sendConfirm'])->name('sendConfirm');
            Route::get('/send-status', [TransfertController::class, 'sendStatus'])->name('sendStatus');
            Route::get('/send-status', [TransfertController::class, 'sendStatus'])->name('sendStatus');
        
            Route::get('nouveau', [TransfertController::class, 'create'])->name('create');

            Route::post('transferer', [TransfertController::class, 'store'])->name('store');
        });

        Route::prefix('rechargement')->name('rechargement.')->group(function () {
            Route::get('/', [RechargementController::class, 'index'])->name('index');

            Route::get('{moyenRechargement}', [RechargementController::class, 'create'])->name('create');

            Route::post('store/{moyenRechargement}', [RechargementController::class, 'store'])->name('store');
        });

        Route::prefix('retrait')->name('retrait.')->group(function () {
            Route::get('/', [RetraitController::class, 'index'])->name('index');

            Route::get('create', [RetraitController::class, 'create'])->name('create');
        });

        /* * Pour les paiements
        Route::prefix('paiement')->name('paiement.')->group(function () {
            Route::get('/', function () {
                return view('client.paiement.index');
            })->name('index');

            Route::get('{paiement}', function ($paiement) {
                if ($paiement == 'canal-plus') {
                    $data = [
                        'title' => 'Canal plus',
                        'img' => asset('images/marchands/canal-plus.png'),
                    ];
                } elseif ($paiement == 'startimes') {
                    $data = [
                        'title' => 'StarTimes',
                        'img' => asset('images/marchands/startimes.png'),
                    ];
                } else {
                }

                return view('client.paiement.create', compact('paiement', 'data'));
            })->name('create');
        }); */
    });
});
