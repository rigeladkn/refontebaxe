<?php

namespace App\Http\Requests;

use App\Http\Traits\FraisTrait;
use App\Models\User;
use App\Models\Transfert;
use Illuminate\Http\Request;
use App\Http\Traits\LocalisationTrait;
use App\Http\Traits\SoldesTrait;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransfertRequest extends FormRequest
{
    use LocalisationTrait, SoldesTrait;

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
    public function rules(Request $request)
    {
        return [
            "pays"            => ['required', 'exists:pays,indicatif'],
            "destinataire"    => ['required', 'exists:users,telephone'],
            "montant"         => ['required', 'numeric'],
            "code_validation" => ['nullable'],
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages()
    {
        return [
            'destinataire.exists' => 'Le destinataire n\'est pas client '.env('APP_NAME'),
        ];
    }

    /**
    * Prepare the data for validation.
    *
    * @return void
    */
    protected function prepareForValidation()
    {
        $this->merge([
            'destinataire' => $this->pays.$this->destinataire,
        ]);
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
            $destinataire = User::where('telephone', $this->destinataire)->first();

            if ($destinataire)
            {
                if (auth()->id() == $destinataire->id)
                {
                    $validator->errors()->add('destinataire', 'Impossible de faire le transfert vers cette destination.');
                }

                /**
                * *S'il a les fonds suffisant
                */
                if ($this->not_required_solde($this->montant) || $this->not_required_solde($this->montant))
                {
                    $validator->errors()->add('montant', 'Solde insuffisant');
                }
            }
        });
    }
}
