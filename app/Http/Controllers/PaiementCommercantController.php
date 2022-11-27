<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaiementCommercantController extends Controller
{
    public function formPaiement()
    {
        return view("paiement-commercant.form-paiement");
    }
}
