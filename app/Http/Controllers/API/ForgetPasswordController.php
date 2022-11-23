<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Mail;
use Illuminate\Pagination\Paginator;
use Notification;
use App\Notifications\ApiResetPasswordNotif;

class ForgetPasswordController extends Controller
{
    protected function sendResetLinkResponse(Request $request)
    {
        $input = $request->only('email');
        $validator = Validator::make($input, [
            'email' => "required"
        ]);
        if ($validator->fails()) {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        if(str_contains($request->email, '@')) {
            $user = User::where('email', $input)->first();
        } else {
            $user = User::where('telephone', $input)->first();
        }

        if($user){
            $code = generateRandomNumber();

            User::where('email', $input)->update(['code_validation' => $code]);

            send_code($request->type, $request->email, $code, "mot_de_passe");

            $response = ['id'=> $user->id, 'code_validation' => $code];
            return response($response, 200);

        }else{
            return response()->json([
                'errors' => [
                    'email' => "Aucun utilisateur n'est trouver avec cette email"
                ]
            ], 422);
        }

    }

}
