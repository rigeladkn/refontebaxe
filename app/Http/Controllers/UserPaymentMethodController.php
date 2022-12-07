<?php

namespace App\Http\Controllers;

use App\Models\UserPaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserPaymentMethodController extends Controller
{
    //

    public function addPaymentCard(Request $request)
    {
        // try {
            // dd($request);
            $data = $request->validate([
                'type' => 'required',
                'number' => 'required|size:16',
                'CVV' => 'required|size:3',
                'holder' => 'required',
            ]);

            $expDate = explode('/', $request['expirationDate']);
            $month = $expDate[0];
            $year = '20'.$expDate[1];
            $date = Carbon::createFromDate($year, $month)->endOfMonth();
            $today = Carbon::now();
            if ($today->diffInDays($date) < 0) {
                dd($date,$today,'invalide date');
                return redirect()->back()->with([
                    "success" => false,
                    "message" => "Carte invalide : la carte a expiré",
                ]);
            }
            
            // dd($data);
            $data["expirationDate"] = $date;
            $data["user_id"] = auth()->user()->id;
            //make a request to check if card is valid
            UserPaymentMethod::create($data);
            return redirect()->back()->with([
                "success" => true,
                "message" => "Carte ajoutée avec succès",
            ]);
        // } catch (\Throwable $th) {
        //     return redirect()->back()->with([
        //         "success" => false,
        //         "message" => "Carte non valide",
        //     ]);
        // }
    }


    public function deletePaymentMethod(Request $request){
        try {
            $id = $request["paymentMethId"];
            $payMeth = UserPaymentMethod::find($id);
            $payMeth->delete($id);
            return redirect()->back()->with([
                "success" => true,
                "message" => "Moyen de payement supprimé avec succès",
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with([
                "success" => false,
                "message" => "Un problème a été rencontré lors de la suppression",
            ]);
        }
    }
}
