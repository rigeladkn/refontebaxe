<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Guzzle\Http\Client;


class ResetPasswordController extends Controller
{
    protected function sendResetResponse(Request $request){
        $input = $request->all();
        // $input = $request->only('email', 'password', 'password_confirmation');
        $validator = Validator::make($input, [
            'id'                 => ['required'],
            'password'              =>  ['required', 'min:8'],
            'password_confirmation' => ['required_with:password','same:password','min:8'],
        ]);
        if ($validator->fails()) {
            return response(['errors'=> $validator->errors()->all()], 422);
        }

        $user = User::where('id', $request->id)->first();

        if($user) {
            $token = $user->createToken('API Token Login')->plainTextToken;

            User::where('id', $request->id)->update([
                'password' => Hash::make($request->password)
            ]);

            $user = User::where('id', $request->id)->first();

            $credentials = [
                'email' => $user->email,
                'password' => $request->password,
            ];

            auth()->attempt($credentials);

            $token = auth()->user()->createToken('API Token Login')->plainTextToken;

            $ip_register = auth()->user()->ip_register == '127.0.0.1' ? (env('APP_ENV') == 'production' ? request()->ip() : auth()->user()->ip_register) : auth()->user()->ip_register;

            $recent_ip = env('APP_ENV') == 'production' || auth()->user()->recent_ip == '127.0.0.1' ? request()->ip() : auth()->user()->ip_register;

            auth()->user()->update([
                'ip_register' => $ip_register,
                'recent_ip'   => $recent_ip,
            ]);

            auth()->user()->informations = Gate::allows('is-client') ? auth()->user()->client : auth()->user()->distributeur;

            return response(['user' => auth()->user(), 'pays' => auth()->user()->pays, 'token' => $token], 200);

        }else{
            return response()->json([
                'errors' => [
                    'email' => "Aucun utilisateur n'est trouver avec cette email"
                ]
            ], 401);
        }
    }


}
