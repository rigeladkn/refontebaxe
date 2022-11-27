<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Depot;
use App\Http\Traits\FraisTrait;
use App\Http\Traits\SoldesTrait;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class StoreDepotRequest extends FormRequest
{
    use SoldesTrait, FraisTrait;

    /**
    * Determine if the user is authorized to make this request.
    *
    * @return bool
    */
    public function authorize()
    {
        return true;
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array
    */
    public function rules()
    {
        return [
            "client_id"       => ['required', 'exists:users,id'],
            "client_montant"  => ['required', 'numeric'],
            "montant"         => ['required', 'numeric', 'min:1'],
            "code_validation" => ['required']
        ];
    }

    /**
    * Configure the validator instance.
    *
    * @param  \Illuminate\Validation\Validator  $validator
    * @return void
    */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $client = User::find($this->client_id);

            /**
            * *S'il a les fond suffissant
            */
            if ($this->not_required_solde($this->montant))
            {
                $validator->errors()->add('montant', 'Solde insuffisant');
            }

            if ( ! ($this->frais_get_commission_depot_distributeur(Depot::class, auth()->user(), $this->montant)) )
            {
                $validator->errors()->add('exeption_error', "<p>Désolé vous ne pouvez pas effectuer cette opération.</p> <p>Si vous pensez qu'il s'agit d'une erreur contacter le service client.</p>");
            }

            if (Gate::forUser($client)->denies('is-client'))
            {
                $validator->errors()->add('client_id', "Impossible de faire le dépôt pour ce client.");
            }

            if (!Hash::check($this->code_validation, auth()->user()->code_validation))
            {
                $validator->errors()->add('code_validation', 'Code de validation incorrect.');
            }
        });
    }
}
