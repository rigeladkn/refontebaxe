<?php

namespace App\Http\Controllers;

use App\Models\Transfert;
use App\Http\Traits\LocalisationTrait;
use App\Http\Requests\StoreTransfertRequest;
use App\Http\Requests\UpdateTransfertRequest;
use App\Http\Traits\TauxTrait;
use Illuminate\Support\Str;

class TransfertController extends Controller
{
    use LocalisationTrait, TauxTrait;

    public function __construct()
    {
        // $this->middleware('code.confirmation')->only(['create', 'store']);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $transferts_from = auth()->user()->client->transferts_from;

        $transferts_to = auth()->user()->client->transferts_to;

        $transferts = collect($transferts_from)->merge($transferts_to)->sortByDesc('created_at');

        return view('dashboard.send.index', compact('transferts'));

    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('client.transfert.create');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \App\Http\Requests\StoreTransfertRequest  $request
    * @return \Illuminate\Http\Response
    */
    public function store(StoreTransfertRequest $request)
    {
        //
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Transfert  $transfert
    * @return \Illuminate\Http\Response
    */
    public function show(Transfert $transfert)
    {
        //
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Transfert  $transfert
    * @return \Illuminate\Http\Response
    */
    public function edit(Transfert $transfert)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \App\Http\Requests\UpdateTransfertRequest  $request
    * @param  \App\Models\Transfert  $transfert
    * @return \Illuminate\Http\Response
    */
    public function update(UpdateTransfertRequest $request, Transfert $transfert)
    {
        //
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Transfert  $transfert
    * @return \Illuminate\Http\Response
    */
    public function destroy(Transfert $transfert)
    {
        //
    }
}
