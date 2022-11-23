<?php

namespace App\Http\Controllers;

use App\Http\Traits\LocalisationTrait;
use App\Http\Traits\TauxTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class QRCodeController extends Controller
{
    use TauxTrait, LocalisationTrait;

    public function reade_depot(Request $request)
    {
        if (request()->ajax())
        {
            if (Gate::denies('is-distributeur')) abort(403);

            if (!array_key_exists('client_id', $request->all()) || !array_key_exists('client_montant', $request->all())) abort(400);

            $client = User::find($request->client_id);

            $client ? '' : abort(400);

            $client->montant = $request->client_montant;

            if (!$this->same_country_users(auth()->user(), $client))
            {
                $client->montant = $this->taux_convert($client->pays->symbole_monnaie, auth()->user()->pays->symbole_monnaie, $client->montant);
            }

            return view('distributeur.depot.partials.form-depot', compact('client'));
        }

        abort(400);
    }

    public function reade_retrait(Request $request)
    {
        if ($request->ajax())
        {
            if (Gate::denies('is-distributeur')) abort(403);

            if (!array_key_exists('client_id', $request->all()) || !array_key_exists('client_montant', $request->all())) abort(400);

            $client = User::find($request->client_id);

            $client ? '' : abort(400);

            $client->montant = $request->client_montant;

            if (!$this->same_country_users(auth()->user(), $client))
            {
                $client->montant = (int) $this->taux_convert($client->pays->symbole_monnaie, auth()->user()->pays->symbole_monnaie, $client->montant);
            }

            return view('distributeur.retrait.partials.form-retrait', compact('client'));
        }
    }
}
