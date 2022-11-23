<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // dd(auth()->user());

        $data["user"] = "";
        $data["flag"] = auth()->user()->pays->url_drapeau;
        $solde = getUserSolde(auth()->user());
        $data["solde"] = format_number_french($solde ?? 0, 2);
        $data['devise'] = auth()->user()->pays->symbole_monnaie;
        // dd(auth()->user());
        // User::where('email',auth()->user);
        $account_status = [
            'isPhoneVerified' => auth()->user()->is_phone_valid,
            'isEmailVerified'  => auth()->user()->is_email_valid,
            'hasAnAccount' => 0,
            'hasACard' => 0,
        ];
        $percentage = 0;
        foreach ($account_status as $key => $value) {
            if($value == 1){
                $percentage = $percentage + 1 ;
            }
        }
        
        $account_status["percentage"] = intval(($percentage / 4)*100);

        return view('dashboard.index', [
            "data" => $data,
            "account_status" => $account_status
        ]);
    }

    public function transactions(Request $request)
    {
        return view('dashboard.transactions');
    }

    public function send(Request $request)
    {
        return view('dashboard.send.index');
    }

    public function sendConfirm(Request $request)
    {
        return view('dashboard.send.confirm');
    }

    public function sendStatus(Request $request)
    {
        return view('dashboard.send.status');
    }

    public function deposit(Request $request)
    {
        // dd(auth()->user());
        return view('dashboard.deposit.index');
    }
}
