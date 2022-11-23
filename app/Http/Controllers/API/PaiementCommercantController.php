<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ClientService; 
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaiementCommercantController extends Controller
{
    private $clientService;
    public function __construct(ClientService $clientService)
    {
        $this->clientService = $clientService;
    }
    public function store(Request $request)
    {
        
        $data= []; 
        
        $validator = Validator::make($request->all(), [
            'commercantPhone' => 'bail|required|string',
            'montant' => 'bail|required|numeric|gt:0',
            'confirmed' => 'nullable'
        ]);
        DB::beginTransaction();
        $currentUser = auth()->user();
        $request->confirmed = $request->confirmed == null ? '0': $request->confirmed;
        try{
            if($validator->failed()){
                throw new Error($validator->getMessageBag()->first());
            }
            if($request->confirmed == '1'){
                $data["data"] = $this->clientService->payToCommercant(
                    $request->commercantPhone, 
                    $currentUser, 
                    $request->montant
                );
                $data["message"] = "Paiement effectue avec succes";
            }else{
                $data["data"] = $this->clientService->getPaymentRecap(
                    $request->commercantPhone, 
                    $currentUser, 
                    $request->montant
                );
                $data["message"] = "Pour recap";
            }
           
            $data["status"] = "ok";
           
            DB::commit();
        }catch(\Throwable $e){
            $data["status"] = "ko";
            $data["message"] = $e->getMessage();
            DB::rollBack();
        }
        return response()->json($data,200);
    }


}
