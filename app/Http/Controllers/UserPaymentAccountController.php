<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPaymentAccount;

class UserPaymentAccountController extends Controller
{
    

    public function addBankAccount(Request $request)
    {
        // try {
            // dd($request);
            // dd($request);
            $data = $request->validate([
                'bank_name' => 'required',
                'number' => 'required|size:16',
            ]);
            $data["user_id"] = auth()->user()->id;
            //make a request to check if card is valid
            UserPaymentAccount::create($data);
            return redirect()->back()->with([
                "success" => true,
                "message" => "Compte ajouté avec succès",
            ]);
        // } catch (\Throwable $th) {
        //     return redirect()->back()->with([
        //         "success" => false,
        //         "message" => "Carte non valide",
        //     ]);
        // }
    }


    public function deleteBankAccount(Request $request){
        try {
            $id = $request["accountId"];
            $account = UserPaymentAccount::find($id);
            $account->delete($id);
            return redirect()->back()->with([
                "success" => true,
                "message" => "Compte supprimé avec succès",
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with([
                "success" => false,
                "message" => "Un problème a été rencontré lors de la suppression",
            ]);
        }
    }
}
