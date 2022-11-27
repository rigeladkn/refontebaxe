<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Http;

trait CountriesTrait
{
    protected $countries_url = "https://restcountries.com/v3.1/";
    
    public function countries_all()
    {
        try
        {
            $response = Http::withOptions(['verify' => config('app.debug') ? false : true])->get($this->countries_url.'all');
            
            // return $response->json();
            $prefixe = [];

            foreach ($response->json() as $countrie)
            {
                $prefixe[] = $countrie['cca2'];
            }

            return $prefixe;
        }
        catch (\Throwable $th)
        {
            dd($th);
        }
    }

    public function countries_list_of_codes(Array $codes)
    {
        try
        {
            $response = Http::withOptions(['verify' => config('app.debug') ? false : true])->get($this->countries_url.'alpha?codes='.implode(",", $codes));
            
            return $response->json();
        }
        catch (\Throwable $th)
        {
            dd($th);
        }
    }
}