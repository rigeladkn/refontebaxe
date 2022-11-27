<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Services\DistributeurService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DistributeurUserSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    protected $distributeurService;

    public function __construct(DistributeurService $distributeurService)
    {
      $this->distributeurService = $distributeurService;
    }
    public function handle(  Request $request, Closure $next)
    {
        $currentClientSession = $this->distributeurService->findCurrentClientSession(Auth::user()->id);
        if ( $currentClientSession == null) return $next($request);
        $currentClient= Client::where('id','=', $currentClientSession->id_client)->first();
        $request->attributes->add(['distributeurCurrentClient'=> $currentClient]);
        return $next($request);
    }
}
