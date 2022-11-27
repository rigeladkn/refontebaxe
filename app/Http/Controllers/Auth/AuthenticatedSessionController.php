<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
    * Display the login view.
    *
    * @return \Illuminate\View\View
    */
    public function create()
    {
        return view('auth.login');
    }

    /**
    * Handle an incoming authentication request.
    *
    * @param  \App\Http\Requests\Auth\LoginRequest  $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $ip_register = auth()->user()->ip_register == '127.0.0.1' ? (env('APP_ENV') == 'production' ? request()->ip() : auth()->user()->ip_register) : auth()->user()->ip_register;

        $recent_ip = env('APP_ENV') == 'production' ? request()->ip() : auth()->user()->ip_register;

        auth()->user()->update([
            'ip_register' => $ip_register,
            'recent_ip'   => $recent_ip,
        ]);

        return redirect()->route('dashboard');
    }

    /**
    * Destroy an authenticated session.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
