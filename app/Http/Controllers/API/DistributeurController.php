<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Distributeur;
use App\Services\DistributeurService;
use Illuminate\Http\Request;

class DistributeurController extends Controller
{

    private $distributeurService; 
    
    public function __construct(DistributeurService $distributeurService)
    {
        $this->distributeurService = $distributeurService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [];
        try{
            $distributeurs = $this->distributeurService->findDistributeurs($request);
            $data["status"] = "ok";
            $data["data"] =  $distributeurs;
        }catch(\Throwable $e){
            $data["status"] = "ko";
            $data["message"] =  $e->getMessage();
        }
        
            return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
