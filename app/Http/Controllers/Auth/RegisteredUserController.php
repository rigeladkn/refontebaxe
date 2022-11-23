<?php

namespace App\Http\Controllers\Auth;

use App\Models\Pays;
use App\Models\User;
use App\Models\Client;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\LocalisationTrait;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;

class RegisteredUserController extends Controller
{
    use LocalisationTrait;

    protected $pays = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');

        $localisation = $this->get_geolocation();

        $this->pays = Pays::where('code', $localisation['country_code2'])->first();
    }
    /**
    * Display the registration view.
    *
    * @return \Illuminate\View\View
    */
    public function create()
    {
        $localisation = $this->pays;

        return view('auth.register', compact('localisation'));
    }

    /**
    * Handle an incoming registration request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\RedirectResponse
    *
    * @throws \Illuminate\Validation\ValidationException
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'nom'         => ['required', 'string', 'max:255'],
            'prenoms'     => ['required', 'string', 'max:255'],
            'code_postal' => ['required', 'string', 'max:255'],
            'ville'       => ['required', 'string', 'max:255'],
        ]);

        $validator->after(function ($validator) use ($request) {
            if (str_starts_with($request->telephone, '00') || str_starts_with($request->telephone, '+'))
            {
                $validator->errors()->add('telephone', "La valeur du champ doit être saisi sans l'indicatif du pays.");
            }

            $telephone = $this->pays->indicatif.$request->telephone;

            if (User::where('telephone', $telephone)->first())
            {
                $validator->errors()->add('telephone', "La valeur du champ est déjà utilisée.");
            }
        });

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'pays_register_id' => $this->pays->id,
            'ip_register'      => request()->ip(),
            'telephone'        => str_replace(' ', '', $this->pays->indicatif.$request->telephone),
            'email'            => strtolower($request->email),
            'password'         => Hash::make($request->password),
            'recent_ip'        => request()->ip(),
        ]);

        $client = Client::create([
            'reference'  => Str::random(10),
            'user_id'     => $user->id,
            'pays_id'     => $this->pays->id,
            'nom'         => ucwords(strtolower($request->nom)),
            'prenoms'     => ucwords(strtolower($request->prenoms)),
            'code_postal' => $request->code_postal,
            'ville'       => ucwords($request->ville),
            'email'       => $request->email,
            'telephone'   => str_replace(' ', '', $this->pays->indicatif.$request->telephone),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }


}
