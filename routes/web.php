<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\UserPaymentMethodController;
use App\Http\Controllers\UserPaymentAccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

if (env('APP_ENV') == 'production') {
    URL::forceScheme('https');
}

Route::get('/', [WelcomeController::class, 'index']);
Route::get('/contact', [WelcomeController::class, 'contact'])->name('contact');
Route::get('/about', [WelcomeController::class, 'about'])->name('about');
Route::get('/signup', [WelcomeController::class, 'signup'])->name('signup');
Route::post('/signup', [AuthenticationController::class, 'register']);
Route::get('/login', [WelcomeController::class, 'login'])->name('login');
Route::post('/login', [AuthenticationController::class, 'login']);
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
Route::get('/validateSms', [AuthenticationController::class, 'showSmsValidationForm'])->name('validateSmsCodeForm');

//Offcial for code validation
Route::match(["get", "post"], "/validation/code", [AuthenticationController::class, "validateCode"]);
Route::post("/resendemailcode", [AuthenticationController::class, "resendEmailCode"]);
Route::post("/resendsmscode", [AuthenticationController::class, "resendSmsCode"]);
//

//
Route::get('/api/validation/{codeDetails}', [AuthenticationController::class, 'validateCode']);

Route::middleware(['auth', 'verified', 'ip.valid'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/transactions', [HomeController::class, 'transactions'])->name('transactions');
    // //send
    // Route::get('/send', [HomeController::class, 'send'])->name('send');
    // Route::get('/send-confirm', [HomeController::class, 'sendConfirm'])->name('sendConfirm');
    // Route::get('/send-status', [HomeController::class, 'sendStatus'])->name('sendStatus');

    //deposit
        Route::get('/deposit', [HomeController::class, 'deposit'])->name('deposit');
    //profil
        Route::get('/profil', [UserController::class, 'profile'])->name('profile');
        Route::get('/cardsAndAccounts', [UserController::class, 'cardsAndAccounts'])->name('cardsAndAccounts');
    //ADD PAYMENT CARDS
        Route::post('/addCard', [UserPaymentMethodController::class, 'addPaymentCard'])->name('addPaymentCard');
        Route::post('/deletePayMeth', [UserPaymentMethodController::class, 'deletePaymentMethod'])->name('deletePaymentMethod');
    //ADD BANK ACCOUNT NUMBER
    Route::post('/addBankAccount', [UserPaymentAccountController::class, 'addBankAccount'])->name('addBankAccount');
    Route::post('/deleteBankAccount', [UserPaymentAccountController::class, 'deleteBankAccount'])->name('deleteBankAccount');
    //UPDATE PASSWORD
    Route::post('/updatePassword', [UserController::class, 'updatePassword'])->name('updatePassword');
        
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
