<?php

namespace App\Http\Traits;

use App\Models\Taux;
use Illuminate\Support\Facades\Http;

trait TauxTrait
{
    protected $taux_url = "https://api.fastforex.io/";

    /**
     * call_url
     *
     * @param  mixed $type
     * @param  mixed $parms
     * @return void
     */
    private function call_url($type, $parms = '')
    {
        return $this->taux_url.$type.'?api_key=e44b0dba82-2e12380ec4-r4b54g&'.$parms;
    }

    /**
     * taux_fetch_all
     *
     * @param  mixed $from
     * @return void
     */
    public function taux_fetch_all(String $from = null)
    {
        try
        {
            $response = Http::get($this->call_url('fetch-all', 'from='.$from));

            return $response->json();
        }
        catch (\Throwable $th)
        {
            dd($th);
        }
    }


    /**
     * taux_fetch_one
     *
     * @param  mixed $from
     * @param  mixed $to
     * @return void
     */
    public function taux_fetch_one(String $from, String $to)
    {
        try
        {
            $response = Http::get($this->call_url('fetch-one', 'from='.$from.'&to='.$to));

            $responseL = $this->taux_fetch_local($from, $to);
            if(isset($responseL)){
                return $responseL->taux;
            } 

            return $response->json()['result'][$to];
        }
        catch (\Throwable $th)
        {
            dd($th);
        }
    }

    public function taux_convert(String $from, String $to, Float $amount)
    {
        try
        {
            $response = Http::get($this->call_url('convert', 'from='.$from.'&to='.$to.'&amount='.$amount));
            $responseL = $this->taux_convert_local($from, $to, $amount);
            if(isset($responseL)){
                return $responseL;
            } 
            return $response->json()['result'][$to];
        }
        catch (\Throwable $th)
        {
            dd($th);
        }
    }

    public function taux_fetch_local(String $from, String $to){
        try{
            $columnToSelect = [ 
                "id"
                , "dateTaux"
                , "pays_to"
                , "pays_from"
                , "taux"
            ];
            $taux_change = Taux::select($columnToSelect)->where("pays_to", "=", $to)->where("pays_from", "=", $from)->first();
            return $taux_change;
        } catch(\Throwable $th){
            dd($th);
        }
    }

    public function taux_convert_local(String $from, String $to, Float $amount){
        try
        {
            $response = $this->taux_fetch_one($from, $to);

            return $amount*$response;
        }
        catch (\Throwable $th)
        {
            dd($th);
        }
    }
}
