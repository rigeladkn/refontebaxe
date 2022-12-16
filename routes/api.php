<?php
use App\Http\Controllers\API\AgenceController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PaysController;
use App\Http\Controllers\API\DepotController;
use App\Http\Controllers\API\SoldeController;
use App\Http\Controllers\API\RetraitController;
use App\Http\Controllers\API\TransfertController;
use App\Http\Controllers\API\AuthenticationController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\DistributeurController;
use App\Http\Controllers\API\ForgetPasswordController;
use App\Http\Controllers\API\ResetPasswordController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\PaiementCommercantController;
use App\Http\Controllers\API\TauxController;
use App\Models\Pays;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::apiResource('clients',ClientController::class);
Route::apiResource('agences',AgenceController::class);
Route::apiResource('pays',PaysController::class);
Route::apiResource('distributeurs',DistributeurController::class);
Route::post('open-distributeur-client-session', [AuthenticationController::class, 'openUserDistributeurSession']);

Route::get('chat-token',[ChatController::class, 'token']);
Route::get('chat-employes',[ChatController::class, 'employes']);
Route::get('chat-clients',[ChatController::class, 'clients']);
Route::get('chat-twilio-client',[ChatController::class, 'createTwilioClient']);
Route::get('taux/fetch', [TauxController::class, 'fetch']);
Route::get('taux/convert', [TauxController::class, 'convert']);




Route::post('login', [AuthenticationController::class, 'login'])->name("apilogin");

Route::post('password/forget-password', [ForgetPasswordController::class, 'sendResetLinkResponse'])->name('passwords.sent');
Route::post('password/reset', [ResetPasswordController::class, 'sendResetResponse'])->name('passwords.reset');

Route::post('register', [AuthenticationController::class, 'register_client']);

Route::post('renvoyer-code', [AuthenticationController::class, 'resend_code']);

//Offcial for code validation
Route::match(["get","post"],"/validation/code",[AuthenticationController::class,"validateCode"]);
Route::post("/resendemailcode",[AuthenticationController::class,"resendEmailCode"]);
Route::post("/resendsmscode",[AuthenticationController::class,"resendSmsCode"]);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('paiement-commercant', [PaiementCommercantController::class, 'store']);
    Route::post('verif_password', [UserController::class, 'verif_password'])->name('verification-password');
    Route::put('user-update', [UserController::class, 'update'])->name('user-update');

    Route::post('code-validation', [AuthenticationController::class, 'code_validation']);

    Route::group(['middleware' => ['can:is-client']], function () {
        Route::prefix('transferts')->name('transfert.')->group(function () {
            Route::post('/', [TransfertController::class, 'store2'])->name('store');
        });

        Route::prefix('rechargement')->name('rechargement.')->group(function () {
            Route::post('carte-credit', [DepotController::class, 'carte_credit']);
        });
    });

    Route::middleware(['can:is-distributeur'])->group(function () {
        Route::post('depot', [DepotController::class, 'store']);

        Route::post('retrait', [RetraitController::class, 'store']);
    });

    Route::get('solde', [SoldeController::class, 'index']);

    Route::post('logout', [AuthenticationController::class, 'logout']);
});





