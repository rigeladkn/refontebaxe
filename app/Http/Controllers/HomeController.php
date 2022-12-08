<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPaymentMethod;
use App\Models\UserPaymentAccount;
use Illuminate\Support\Facades\Auth;
use App\Repositories\SoldeRepository;

class HomeController extends Controller
{

    public function index(Request $request, SoldeRepository $repository)
    {
        // dd($request);
        // dd($this->user);
        $data["user"] = "";
        $data["flag"] = auth()->user()->pays->url_drapeau;
        $solde = getUserSolde(auth()->user());
        $data["solde"] = format_number_french($solde ?? 0, 2);
        $data['devise'] = auth()->user()->pays->symbole_monnaie;
        $hasAnAccount = count(UserPaymentAccount::where('user_id',auth()->user()->id)->get()) > 0 ? 1 : 0;
        $hasACard = count(UserPaymentMethod::where('user_id',auth()->user()->id)->get()) > 0 ? 1 : 0;
        // dd(auth()->user());
        // User::where('email',auth()->user);
        $account_status = [
            'isPhoneVerified' => auth()->user()->is_phone_valid,
            'isEmailVerified'  => auth()->user()->is_email_valid,
            'hasAnAccount' => $hasAnAccount,
            'hasACard' => $hasACard ,
        ];
        $percentage = 0;
        foreach ($account_status as $key => $value) {
            if($value == 1){
                $percentage = $percentage + 1 ;
            }
        }
        
        $account_status["percentage"] = intval(($percentage / 4)*100);

        $transactionsContent = $repository->getHistories(auth()->user(), "dashboard")['transactionsContent'];
        $transactions = $repository->getHistories(auth()->user(), "dashboard")['transactions'];
    //    dd($transactionsContent, $transactions);
        return view('dashboard.index', [
            "data" => $data,
            "account_status" => $account_status,
            "transactions" => $transactions,
            "transactionsContent" => $transactionsContent,
        ]);
    }

    public function transactions(Request $request, SoldeRepository $repository)
    {
        $data["user"] = "";
        $data["flag"] = auth()->user()->pays->url_drapeau;
        $solde = getUserSolde(auth()->user());
        $data["solde"] = format_number_french($solde ?? 0, 2);
        $data['devise'] = auth()->user()->pays->symbole_monnaie;
        $transactionsContent = $repository->getHistories(auth()->user(), "dashboard")['transactionsContent'];
        $transactions = $repository->getHistories(auth()->user(), "dashboard")['transactions'];

        // dd($transactionsContent);
        return view('dashboard.transactions', compact('data','transactionsContent','transactions'));
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
