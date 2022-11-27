<?php

namespace App\Http\Controllers\API;

use App\Models\Pays;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaysController extends Controller
{
    public function index(Request $request)
    {
        

        $data = [];
        try{
            // $recordsCount = isset($request->records_count) ? $request->records_count : 20;
            $recordsCount = isset($request->records_count) ? $request->records_count : 20;
            $pays = Pays::paginate(sizeof(Pays::all()));
            $data["status"] = "ok"; 
            $data["data"] = $pays;
        }catch(\Throwable $e){
            $data["status"] = "ok";
            $data["message"] = $e->getMessage(); 
        }
        return response()->json($data, 200);
    }
}
