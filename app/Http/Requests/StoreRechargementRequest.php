<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LVR\CreditCard\CardCvc;
use LVR\CreditCard\CardNumber;
use LVR\CreditCard\CardExpirationDate;
use Illuminate\Validation\Rule;

class StoreRechargementRequest extends FormRequest
{
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
        if ($this->old_carte)
        {
            return [
                'old_carte' => [
                    'required',
                    Rule::exists('carte_credits', 'id')->where(function ($query) {
                        return $query->where('user_id', auth()->id());
                    })
                ],
                "montant"   => ['required', 'numeric', 'min:1'],
                'cvv' => ['required', new CardCvc(auth()->user()->cartes_credits->where('id', $this->old_carte)->first()->numero)]
            ];
        }
        else
        {
            return [
                "numero"    => ['required', new CardNumber],
                "titulaire" => ['required', 'string', 'min:5'],
                "date"      => ['required', new CardExpirationDate('Y-m')],
                "cvv"       => ['required', new CardCvc($this->get('numero'))],
                "montant"   => ['required', 'numeric', 'min:1']
            ];
        }
    }
}
