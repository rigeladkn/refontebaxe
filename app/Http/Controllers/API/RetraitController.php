<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Retrait;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Traits\TauxTrait;
use App\Http\Traits\FraisTrait;
use App\Http\Traits\SoldesTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\LocalisationTrait;
use Illuminate\Support\Facades\Validator;

class RetraitController extends Controller
{
    use SoldesTrait, TauxTrait, LocalisationTrait, FraisTrait;

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id'       => ['required', 'integer', 'exists:users,id'],
            'client_montant'  => ['required', 'numeric', 'min:1'],
            'resume'          => ['required', 'in:0,1'],
            // 'code_validation' => ['required'],
        ]);

        $client = User::find($request->client_id);

        $validator->after(function ($validator) use ($client) {
            if ($this->not_required_solde(request()->client_montant, $client))
            {
                $validator->errors()->add('client_montant', 'Solde du client insuffisant');
            }

            $montant = request()->client_montant;

            if (!$this->same_country_users(auth()->user(), $client))
            {
                $montant = (int) $this->taux_convert($client->pays->symbole_monnaie, auth()->user()->pays->symbole_monnaie, $montant);
            }

            // Lorsqu'on arrive pas à recuperé la tranche de commission
            if ( ! ($this->frais_get_commission_depot_distributeur(Retrait::class, auth()->user(), $montant)) && $montant > 0)
            {
                $validator->errors()->add('exeption_error', "Désolé vous ne pouvez pas effectuer cette opération. \n Si vous pensez qu'il s'agit d'une erreur contacter le service client.");
            }

            if (Gate::forUser($client)->denies('is-client'))
            {
                $validator->errors()->add('client_id', "Impossible de faire le retrait de ce client.");
            }

            // if (!Hash::check(request()->code_validation, auth()->user()->code_validation))
            // {
            //     $validator->errors()->add('code_validation', 'Code de validation invalide');
            // }
        });

        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Montant à mettre sur le compte du client
        $montant = $request->client_montant;

        if (!$this->same_country_users(auth()->user(), $client))
        {
            $montant = (int) $this->taux_convert($client->pays->symbole_monnaie, auth()->user()->pays->symbole_monnaie, $montant);
        }

        if ($request->resume == 1)
        {
            $client->nom_prenoms = $client->client->noms();

            $client->montant = $montant;

            return response()->json([
                'data' => $client,
            ], 200);
        }

        $commission = $this->frais_get_commission_depot_distributeur(Retrait::class, auth()->user(), $montant);

        $commission = $commission->frais_fixe ?: convertir_un_pourcentage_en_nombre($commission->frais_pourcentage, $montant);

        $taux_to = $this->taux_fetch_one($client->pays->symbole_monnaie, auth()->user()->pays->symbole_monnaie);

        $retrait = Retrait::create([
            'reference'    => Str::random(10),
            'user_id_from' => $client->id,
            'user_id_to'   => auth()->id(),
            'montant'      => $request->client_montant,
            'frais'        => 0,
            'taux_from'    => 1,
            'taux_to'      => $taux_to,
            'pays_from'    => env('APP_ENV') == 'production' ? $this->get_geolocation($client->recent_ip)['country_code2'] : $client->pays->code,
            'pays_to'      => auth()->user()->pays->code,
            'ip_from'      => $client->recent_ip,
            'ip_to'        => env('APP_ENV') == 'production' ? request()->ip() : auth()->user()->recent_ip
        ]);

        if ($retrait)
        {
            $this->set_solde($client, $retrait->id, Retrait::class, $this->new_solde_user_is_from($request->client_montant, $client));

            $this->set_solde(auth()->user(), $retrait->id, Retrait::class, $this->new_solde_user_is_to(auth()->user(), $montant));

            auth()->user()->commissions()->create([
                'operation_type' => Retrait::class,
                'operation_id' => $retrait->id,
                'commission' => $commission
            ]);
        }

        $commission_retrait = auth()->user()->commissions->where('operation_type', Retrait::class)->where('statut', false)->sum('commission');

        $message = 'Vous venez de faire le retrait de '.format_number_french($montant, 2).' '.auth()->user()->pays->symbole_monnaie.' de '.$client->noms().' via '.env('APP_NAME').'. Votre nouveau  solde : '.format_number_french(auth()->user()->soldes->last()->actuel, 2).' '.auth()->user()->pays->symbole_monnaie.'. '.env('APP_NAME').' vous remercie pour votre collaboration.';

        return response()->json([
            'data'               => $retrait,
            'commission_retrait' => $commission_retrait,
            'message'            => $message,
        ], 200);
    }
}
