<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CompteBanque;
use Illuminate\Support\Facades\Validator;

class CompteBancaireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        $userConnect = auth()->user();
        // dd($userConnect->id);
        $validator = Validator::make($request->all(), [
            'num_compte_banque'   => ['required', 'string', 'max:255'],
            'nom_banque'          => ['required', 'string', 'max:255'],
            'iban'                => ['required', 'string', 'max:255'],
            'num_piece_identite'  => ['required', 'string', 'max:255'],
            'domiciliation'       => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $compteBanque = CompteBanque::create([
            'num_compte_bancaire'  => $request->num_compte_banque,
            'nom_banque'           => $request->nom_banque,
            'iban'                 => $request->iban,
            'num_piece_identite'   => $request->num_piece_identite,
            'domiciliation'        => $request->domiciliation,
            'user_id'              => $userConnect->id,
        ]);

        if($compteBanque){
            return redirect()->back()->with('message', "Vos informations bancaire sont enregistrés avec succès");
        }else{
            return redirect()->back()->with('error', "Erreur lors");
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $validator = Validator::make($request->all(), [
            'num_compte_banque'   => ['required', 'string', 'max:255'],
            'nom_banque'              => ['required', 'string', 'max:255'],
            'iban'                => ['required', 'string', 'max:255'],
            'num_piece_identite'  => ['required', 'string', 'max:255'],
            'domiciliation'       => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $distributeur = CompteBancaire::create([
            'num_compte_banque'  => $request->num_compte_banque,
            'nom_banque'             => $request->nom_banque,
            'iban'               => $request->iban,
            'num_piece_identite' => $request->num_piece_identite,
            'domiciliation'      => $request->domiciliation,
        ]);

        return redirect()->back()->with('message', "Vos informations bancaire sont enregistrés avec succès");
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
