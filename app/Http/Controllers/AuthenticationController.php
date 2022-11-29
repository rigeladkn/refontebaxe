<?php

namespace App\Http\Controllers;

use App\Models\Pays;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{
    public function register(Request $request)
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
                return redirect()->back()->with([
                    "success" => false,
                    "message" => "La valeur du champ est déjà utilisée"
                ]);
            }
        });

        if ($validator->fails()) {
            dd($validator->errors());
            return redirect()->back()->with([
                "success" => false,
                "message" =>  "Oops ! Un problème a été rencontré lors de l'opération"
            ]);
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
                dd($th);
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

        return redirect()->route('validateSmsCodeForm')->with([
            'success' => true,
            "message" => "Un code a été envoyé au ". $telephone .". Consultez votre messagerie sms ainsi que votre boite de réception email afin de valider votre inscription.",
            "phone" => $telephone
        ]);
        // }

    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($data)) {
            return redirect()->back()->with([
                "success" => false,
                "message" => "Credentials incorrects"
            ]);
            // return response(['error_message' => 'Incorrect Details. 
            // Please try again']);
        }

        if (!auth()->user()->is_email_valid) {
            auth()->user()->tokens()->delete();
            return redirect()->back()->with(["success" => false, "message" => "Vous devez valider votre email"], 403);
        }
        if (!auth()->user()->is_phone_valid) {
            auth()->user()->tokens()->delete();
            return redirect()->back()->with(["success" => false, "message" => "Vous devez valider votre numéro de téléphone"], 403);
        }

        $token = auth()->user()->createToken('API Token Login')->plainTextToken;

        $ip_register = auth()->user()->ip_register == '127.0.0.1' ? (env('APP_ENV') == 'production' ? request()->ip() : auth()->user()->ip_register) : auth()->user()->ip_register;

        $recent_ip = env('APP_ENV') == 'production' || auth()->user()->recent_ip == '127.0.0.1' ? request()->ip() : auth()->user()->ip_register;

        auth()->user()->update([
            'ip_register' => $ip_register,
            'recent_ip' => $recent_ip,
        ]);

        auth()->user()->informations = Gate::allows('is-client') ? auth()->user()->client : auth()->user()->distributeur;
        // return response(['user' => auth()->user(), 'pays' => auth()->user()->pays, 'token' => $token], 200);
        return redirect()->route("home");
    }

    public function showSmsValidationForm(Request $request,$phone){
        return view('smsForm')->with([
            "phone" => $phone
        ]);
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
                    //revoir page de validation 
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
                    return redirect()->route('login')->with(["success" => true, "message" => "Code valide. Connectez-vous pour poursuivre."], 200);
                } else {
                    return redirect()->route('validateSmsCodeForm')->with(["success" => false, "message" => "Code incorect"], 200);
                }
            }
        }
        // Return new client JSON
    }

    


}
