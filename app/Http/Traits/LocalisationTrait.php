<?php

namespace App\Http\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Http;

trait LocalisationTrait
{

    /**
    * get_geolocation
    *
    * @return void
    */
    public static function get_geolocation($ip_source = null)
    {
        $apiKey = 'b601e700ee0549e3b441ec4c8bab72f9';

        // Obtenir son IP : https://www.mon-ip.com/

        if (env('APP_ENV') == 'production' && !$ip_source)
        {
            $ip = request()->ip();
        }
        elseif ($ip_source)
        {
            $ip = $ip_source;
        }
        else
        {
            // $ip = '160.154.151.210'; // CI
            $ip = '93.177.75.198'; // France
            $ip = "41.138.91.175";
        }

        $lang = "fr";

        $fields = "*";

        $excludes = "";

        try
        {
            $response = Http::get("https://api.ipgeolocation.io/ipgeo?apiKey=$apiKey&ip=$ip&lang=$lang&fields=$fields&excludes=$excludes");

            return $response->json();
        }
        catch (\Throwable $th)
        {
            dd($th);
        }
    }

    /**
    * Detecter si l'utilisateur n'a pas changé de pays
    *
    * @param  \App\Models\User  $user
    * @return void
    */
    public function changed_country(User $user)
    {
        return $this->get_geolocation($user->recent_ip)['country_code2'] != $user->pays->code;
    }

    /**
     * same_country_users
     *
     * @param  mixed $user_from
     * @param  mixed $user_to
     * @return void
     */
    public function same_country_users(User $user_from, User $user_to)
    {
        if ($this->get_geolocation($user_from->ip_register)['country_code2'] == $this->get_geolocation($user_to->ip_register)['country_code2'] && $this->get_geolocation($user_from->recent_ip)['country_code2'] == $this->get_geolocation($user_to->recent_ip)['country_code2'])
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * same_continent
     *
     * @param  mixed $user_from
     * @param  mixed $user_to
     * @return void
     */
    public function same_continent_users(User $user_from, User $user_to)
    {
        if ($this->get_geolocation($user_from->ip_register)['continent_name'] == $this->get_geolocation($user_to->ip_register)['continent_name'] && $this->get_geolocation($user_from->recent_ip)['continent_name'] == $this->get_geolocation($user_to->recent_ip)['continent_name'])
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * france_to_france
     *
     * @param  mixed $user_from
     * @param  mixed $user_to
     * @return void
     */
    public function france_to_france_users(User $user_from, User $user_to)
    {
        if ($this->get_geolocation($user_from->ip_register)['country_code2'] == "FR" && $this->get_geolocation($user_to->ip_register)['country_code2'] == "FR" && $this->get_geolocation($user_from->recent_ip)['country_code2'] == "FR" && $this->get_geolocation($user_to->recent_ip)['country_code2'] == "FR")
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * user_europe_to_user_afrique
     *
     * @param  mixed $europe
     * @param  mixed $afrique
     * @return void
     */
    public function user_europe_to_user_afrique(String $europe, String $afrique)
    {
        return ($europe == 'Europe' && $afrique == 'Africa');
    }
}
