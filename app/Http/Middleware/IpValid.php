<?php

namespace App\Http\Middleware;

use App\Http\Traits\SecurityTrait;
use Closure;
use Illuminate\Http\Request;

class IpValid
{
    use SecurityTrait;

    /**
    * Handle an incoming request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
    * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
    */
    public function handle(Request $request, Closure $next)
    {
        /**
        * TODO Enlever cette adresse IP lorsque le site sera en production, ne rien mettre comme paramètre
        */
        $ip = null;

        if (env('APP_ENV') != 'production')
        {
            $ip = '160.154.148.138';
        }

        if (! $this->security_check_ip_vpn($ip))
        {
            return response()->json(['error' => "Nous n'autorisons pas cette connexion."], 403);
        }

        return $next($request);
    }
}
