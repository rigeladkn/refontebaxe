<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\LocalisationTrait;
use App\Models\Client;
use App\Models\Pays;
use App\Models\User;
use App\Services\ClientQrService;
use App\Services\DistributeurService;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthenticationController extends Controller
{
    use LocalisationTrait;
    private $clientQrService;
    private $distributeurService;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(DistributeurService $distributeurService, ClientQrService $clientQrService)
    {
        $this->distributeurService = $distributeurService;
        $this->clientQrService = $clientQrService;
        ///$this->middleware('ip.valid');
    }

    public function checkQrCredentials(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'decoded_text' => 'required',
        ]);
        $data = [];
        try {
            if ($validator->fails()) {
                throw new Error("Aucun texte recu");
            }

            $user = $this->clientQrService->findClientCredentials($request->decoded_text);

            if ($user == null) {
                throw new Error("Client inconnu");
            }

            $data["status"] = "ok";
            $data["data"] = $user;

        } catch (\Throwable $e) {
            $data["status"] = "ko";
            $data["message"] = $e->getMessage() . " " . intval($request->decoded_text);
        }

        return response()->json($data, 200);

    }

    public function openUserDistributeurSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'decoded_qr_text' => 'required',
        ]);
        $data = [];
        try {
            if ($validator->fails()) {
                throw new Error("Aucun texte recu");
            }
            $client = $this->clientQrService->findClientCredentials($request->decoded_qr_text);

            if ($client == null) {
                throw new Error("Client inconnu");
            }

            $this->distributeurService->saveClientSession($client->id, auth()->user()->id);
            $data["status"] = "ok";
            $data["data"] = $client;

        } catch (\Throwable $e) {
            $data["status"] = "ko";
            $data["message"] = $e->getMessage() . " " . intval($request->decoded_qr_text);
        }

        return response()->json($data, 200);

    }

    public static function blob_to_string($bin)
    {
        $char = explode(' ', $bin);
        $userStr = '';
        foreach ($char as $ch) {
            $userStr .= chr(bindec($ch));
        }

        return $userStr;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (str_contains($request->email, '@')) {
            $credentials = [
                'email' => $request->email,
                'password' => $request->password,
            ];

        } else {
            $credentials = [
                'telephone' => $request->email,
                'password' => $request->password,
            ];
        }

        if (!auth()->attempt($credentials)) {
            return response()->json([
                'errors' => [
                    'email' => "Aucun utilisateur n'a été trouvé avec ces identifiants.",
                ],
            ], 422);
        }

        if (!auth()->user()->is_email_valid) {
            auth()->user()->tokens()->delete();
            return response(["success" => false, "message" => "Vous devez valider votre email"], 403);
            // abort(403, 'Vous devez valider votre email.');
        }
        if (!auth()->user()->is_phone_valid) {
            auth()->user()->tokens()->delete();
            return response(["success" => false, "message" => "Vous devez valider votre numéro de téléphone"], 403);

            // abort(403, 'Vous devez valider votre numéro de téléphone.');
        }

        $token = auth()->user()->createToken('API Token Login')->plainTextToken;

        $ip_register = auth()->user()->ip_register == '127.0.0.1' ? (env('APP_ENV') == 'production' ? request()->ip() : auth()->user()->ip_register) : auth()->user()->ip_register;

        $recent_ip = env('APP_ENV') == 'production' || auth()->user()->recent_ip == '127.0.0.1' ? request()->ip() : auth()->user()->ip_register;

        auth()->user()->update([
            'ip_register' => $ip_register,
            'recent_ip' => $recent_ip,
        ]);

        auth()->user()->informations = Gate::allows('is-client') ? auth()->user()->client : auth()->user()->distributeur;

        return response(['user' => auth()->user(), 'pays' => auth()->user()->pays, 'token' => $token], 200);
    }

    public function register_client(Request $request)
    {
        // La validation
        $validator = Validator::make($request->all(), [
            'nom' => ['required', 'string', 'max:255'],
            'prenoms' => ['required', 'string', 'max:255'],
            'code_postal' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'ville' => ['required', 'string', 'max:255'],
            'password' => ['required', 'min:8', 'confirmed'],
            'indicatif' => ['required'],
            "telephone" => ["required"],
        ]);

        $paysUser = Pays::where('indicatif', $request->indicatif)->first();
        $telephone = $paysUser->indicatif . $request->telephone;
        $validator->after(function ($validator) use ($request, $telephone) {
            // if (str_starts_with($request->telephone, '00') || str_starts_with($request->telephone, '+'))
            // {
            //     $validator->errors()->add('telephone', "La valeur du champ doit être saisi sans l'indicatif du pays.");
            // }

            // $adress_datas = $this->get_geolocation();

            // recuperation pays user via code pour avoir l'identifiant du pays
            // $paysUser = Pays::where('code', $request->code_pays)->first();

            // // $telephone = $paysUser->indicatif.$request->telephone;
            // $telephone = $request->telephone;

            if (User::where('telephone', $telephone)->first()) {
                $validator->errors()->add('telephone', "La valeur du champ est déjà utilisée.");
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // $adress_datas = $this->get_geolocation();

        // recuperation pays user via code pour avoir l'identifiant du pays
        // $paysUser = Pays::where('code', $adress_datas['country_code2'])->first();

        $telephone = str_replace(' ', '', $telephone);

        // nouvel user pour les infos de connexion
        $user = User::create([
            'pays_register_id' => $paysUser->id,
            'ip_register' => request()->ip(),
            'email' => strtolower($request->email),
            'telephone' => $telephone,
            'recent_ip' => request()->ip(),
            'password' => Hash::make($request->password),
            'code_validation' => $request->code_validation,
            'email_verified_at' => date('Y-m-d H:i:s'),
        ]);
        // nouvel client
        Client::create([
            'reference' => Str::random(10),
            'nom' => ucwords(strtolower($request->nom)),
            'prenoms' => ucwords(strtolower($request->prenoms)),
            'code_postal' => $request->code_postal,
            'ville' => ucwords(strtolower($request->ville)),
            'email' => strtolower($request->email),
            'telephone' => $telephone,
            'pays_id' => $paysUser->id,
            'user_id' => $user->id,
        ]);
        //send phone first
        $code = generateRandomNumber();
        $user->update(["sms_code" => $code]);
        try {
            send_code("sms", $telephone, $code, "inscription");
        } catch (\Throwable $th) {
            try {
                send_code("sms", $telephone, $code, "inscription");
            } catch (\Throwable $th) {
            }
        }
        // send other code and send email then
        $code = generateRandomNumber();
        $user->update(["email_code" => $code]);
        try {
            send_code("mail", strtolower($request->email), $code, "inscription");
        } catch (\Throwable $th) {
            try {
                send_code("mail", strtolower($request->email), $code, "inscription");
            } catch (\Throwable $th) {
            }
        }

        // event(new Registered($user));

        return response()->json([
            'success' => true,
            "message" => "waiting for user verification",
        ]);
        // }
    }

    public function validateCode(Request $request)
    {
        if ($request->isMethod('get')) {
            if (isset($request->emailCode) && isset($request->email)) {
                $user = User::where('email', $request->email)->get()->first();
                if ($user["email_code"] == $request->emailCode) {
                    $user->update([
                        "is_email_valid" => true,
                    ]);

                    return "Votre adresse email a bien été vérifiée";
                } else {
                    return "Echec de la validation de l'adresse mail";
                }
            } else {
                return "URL Invalide..";
            }
        } else if ($request->isMethod('post')) {
            $data = $request->all();
            $user = User::where('email', $data["email"])->get()->first();
            if (isset($data["smsCode"])) {
                if ($user->sms_code == $data["smsCode"]) {
                    $user->update([
                        "is_phone_valid" => true,
                    ]);
                    return response(["success" => true, "message" => "Code valide"], 200);
                } else {
                    return response(["success" => false, "message" => "Code incorect"], 200);
                }
            }
        }
        // Return new client JSON
    }

    public function resendEmailCode(Request $request)
    {
        $data = $request->all();
        $user = User::where('email', $data["email"])->get()->first();
        if($user == null){
            return response([
                "success" => false,
                "message" => "Aucun utilisateur trouvé! Veuillez-vous enroller en premier"
            ],400);
        }
        $code = generateRandomNumber();
        $user->update(["email_code" => $code]);
        try {
            send_code("mail", strtolower($data["email"]), $code, "inscription");
        } catch (\Throwable $th) {
            try {
                send_code("mail", strtolower($data["email"]), $code, "inscription");
            } catch (\Throwable $th) {
                return response([
                    "success" => false,
                    "message" => "Nous rencontrons un problème à renvoyer le code."
                ],400);
            }
        }
        return response([
            "success" => true,
            "message" => "Un nouveau code a été envoyé sur votre adresse email"
        ],200);
    }

    public function resendSmsCode(Request $request)
    {
        $data = $request->all();
        $user = User::where('email', $data["email"])->get()->first();
        if($user == null){
            return response([
                "success" => false,
                "message" => "Aucun utilisateur trouvé! Veuillez-vous enroller en premier"
            ],400);
        }
        $code = generateRandomNumber();
        $user->update(["sms_code" => $code]);
        $telephone = $user['telephone'];
        try {
            send_code("sms", $telephone, $code, "inscription");
        } catch (\Throwable $th) {
            try {
                send_code("sms", $telephone, $code, "inscription");
            } catch (\Throwable $th) {
                return response([
                    "success" => false,
                    "message" => "Nous rencontrons un problème à renvoyer le code."
                ],400);
            }
        }
        return response([
            "success" => true,
            "message" => "Un nouveau code a été envoyé sur votre adresse email"
        ],200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'logout' => true,
        ], 200);
    }

    public function code_validation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code_validation' => ['required', 'numeric', 'integer'],
            'code_validation_confirmation' => ['required', 'numeric', 'integer', 'same:code_validation'],
        ], ['code_validation_confirmation.same' => "Le code de validation ne correspond pas."]);

        $validator->after(function ($validator) use ($request) {
            if (auth()->user()->code_validation) {
                $validator->errors()->add('code_validation', 'Vous avez déjà un code de validation.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        auth()->user()->update([
            'code_validation' => Hash::make($request->code_validation),
        ]);

        auth()->user()->informations = Gate::allows('is-client') ? auth()->user()->client : auth()->user()->distributeur;

        return response(['user' => auth()->user(), 'pays' => auth()->user()->pays]);
    }

    public function resend_code(Request $request)
    {
        return response()->json([
            "status" => send_code($request->type, $request->to, $request->code, $request->for),
        ]);
    }

}
