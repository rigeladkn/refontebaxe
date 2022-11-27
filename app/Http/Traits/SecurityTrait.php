<?php
namespace App\Http\Traits;

use Illuminate\Support\Facades\Http;

trait SecurityTrait
{
    public $url_check_ip_is_vpn = "https://blackbox.ipinfo.app/lookup/";

    public function security_check_ip_vpn($ip_source = null)
    {
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
        }

        try
        {
            $response = Http::get($this->url_check_ip_is_vpn.$ip);

            $response = $response->body();

            if ($response == 'N')
            {
                return true;
            }

            return false;
        }
        catch (\Throwable $th)
        {
            dd($th);
        }
    }
}
