<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Traits\TauxTrait;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TauxController extends Controller
{

    use TauxTrait;

    public function __construct()
    {
    }
    public function fetch(Request $request)
    {
        
        $data= []; 
        
        $validator = Validator::make($request->all(), [
            'from' => 'bail|required|string',
            'to' => 'bail|required|string'
        ]);

        
        try{
            if($validator->failed()){
                throw new Error($validator->getMessageBag()->first());
            }
            $data["data"] = $this->taux_fetch_one(
                $request->from,
                $request->to
            );
           
            $data["status"] = "ok";
           
        }catch(\Throwable $e){
            $data["status"] = "ko";
            $data["message"] = $e->getMessage();
        }
        return response()->json($data,200);
    }

    public function convert(Request $request)
    {
        
        $data= []; 
        
        $validator = Validator::make($request->all(), [
            'from' => 'bail|required|string',
            'to' => 'bail|required|string',
            'amount' => 'bail|required|numeric|gt:0',
        ]);

        
        try{
            if($validator->failed()){
                throw new Error($validator->getMessageBag()->first());
            }
            $data["data"] = $this->taux_convert(
                $request->from,
                $request->to,
                $request->amount
            );
           
            $data["status"] = "ok";
           
        }catch(\Throwable $e){
            $data["status"] = "ko";
            $data["message"] = $e->getMessage();
        }
        return response()->json($data,200);
    }


}
