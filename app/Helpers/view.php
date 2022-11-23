<?php

use Illuminate\Support\Facades\Request;

if (!function_exists('format_number_phone_ci'))
{
    function format_number_phone_ci($number , $prefixe = null)
    {
        return $prefixe ? $prefixe.' '.preg_replace('/\d{2}/', '$0 ', str_replace('.', '', trim($number)), 4) : preg_replace('/\d{2}/', '$0 ', str_replace('.', '', trim($number)), 4);
    }
}
if(!function_exists('round_somme')){
    function round_somme($nb){
        //return round($nb, 2, PHP_ROUND_HALF_DOWN);
        return floor($nb*100)/100;
    }
}
if (!function_exists('format_number_french'))
{
    function format_number_french($number, $nombre_apres_virgule = 2)
    {
        // ceil($number)
        return is_numeric($number) ? number_format(round_somme($number), $nombre_apres_virgule, ',', ' ') : $number;
    }
}

if (! function_exists('active_route'))
{
    function active_route($route_name)
    {
        return Request::route()->getName() == $route_name ? true : false;
    }
}
