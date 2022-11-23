<?php

namespace App\Http\Controllers;

use App\Models\Frais;
use App\Http\Requests\StoreFraisRequest;
use App\Http\Requests\UpdateFraisRequest;

class FraisController extends Controller
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
     * @param  \App\Http\Requests\StoreFraisRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFraisRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Frais  $frais
     * @return \Illuminate\Http\Response
     */
    public function show(Frais $frais)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Frais  $frais
     * @return \Illuminate\Http\Response
     */
    public function edit(Frais $frais)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFraisRequest  $request
     * @param  \App\Models\Frais  $frais
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFraisRequest $request, Frais $frais)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Frais  $frais
     * @return \Illuminate\Http\Response
     */
    public function destroy(Frais $frais)
    {
        //
    }
}
