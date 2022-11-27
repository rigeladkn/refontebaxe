<?php

namespace App\Http\Controllers;

use App\Models\Virement;
use Illuminate\Http\Request;
use App\Http\Traits\SoldesTrait;
use App\Mail\Virement as MailVirement;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VirementController extends Controller
{
    use SoldesTrait;

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        if (Gate::allows('is-client') || Gate::allows('is-distributeur'))
        {
            $virements = auth()->user()->virements->sortByDesc('created_at');

            $virements = create_pagination_with_collection($virements, 10);

            $virements->withPath(route('virement.index'));

            return view('shared-pages.virement.index', compact('virements'));
        }
        elseif (Gate::allows('is-comptable'))
        {
            $virements = Virement::orderByDesc('created_at')->get();

            $virements = create_pagination_with_collection($virements, 10);

            $virements->withPath(route('virement.index'));

            return view('employe.comptable.virement.index', compact('virements'));
        }
        else
        {
            abort(403);
        }
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        if (Gate::denies('is-client') && Gate::denies('is-distributeur')) abort(403);

        $compte_bancaires = auth()->user()->compte_bancaires;

        return view('shared-pages.virement.create', compact('compte_bancaires'));
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if (Gate::denies('is-client') && Gate::denies('is-distributeur')) abort(403);

        $solde = $this->get_solde() ? $this->get_solde()->actuel : 0;

        $validator = Validator::make($request->all(), [
            'compte_bancaire' => ['required', 'exists:compte_banques,id'],
            'montant'         => ['required', 'numeric', 'min:1', 'max:'.$solde],
        ]);

        $validator->after(function ($validator) use ($request)
        {
            $compte_bancaire = auth()->user()->compte_bancaires->find(request()->compte_bancaire);

            if (! $compte_bancaire) {
                $validator->errors()->add('compte_bancaire', 'Compte bancaire introuvable');
            }

            $virements_en_cours = auth()->user()->virements->where('statut', null)->all();

            if ($virements_en_cours)
            {
                $validator->errors()->add('compte_bancaire', 'Vous avez déjà un virement en cours');
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        auth()->user()->virements()->create([
            'compte_banque_id' => $request->compte_bancaire,
            'montant'          => $request->montant,
            'statut'           => null,
        ]);

        return redirect()->route('virement.index')->with('message', 'Virement lançé avec succès. <br> Vous serez notifié par email lorsque le virement sera effectué.');
    }

    /**
    * Display the specified resource.
    *
    * @param  \App\Models\Virement  $virement
    * @return \Illuminate\Http\Response
    */
    public function show(Virement $virement)
    {
        if (Gate::denies('is-comptable')) abort(403);

        return view('employe.comptable.virement.show', compact('virement'));
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Models\Virement  $virement
    * @return \Illuminate\Http\Response
    */
    public function edit(Virement $virement)
    {
        //
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Models\Virement  $virement
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Virement $virement)
    {
        $validator = Validator::make($request->all(), [
            'action'      => ['required', 'in:virer,refuser'],
            'commentaire' => ['nullable', 'string', 'min:5', Rule::requiredIf($request->action == 'refuser')],
        ],[
            'commentaire.required' => 'Veuillez entrer un commentaire pour refuser le virement',
        ]);

        $validator->after(function ($validator) use ($virement) {
            if ($virement->getRawOriginal('statut') !== null)
            {
                $validator->errors()->add('action', 'Virement déjà traité');
            }
        });

        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $virement->update([
            'employe_id'  => auth()->user()->employe->id,
            'statut'      => $request->action == 'virer' ? true : false,
            'commentaire' => $request->action == 'refuser' ? $request->commentaire : null,
        ]);

        $virement->refresh();

        if ($virement->getRawOriginal('statut') === 1)
        {
            $this->set_solde($virement->initiateur, $virement->id, Virement::class, $this->new_solde_user_is_from($virement->montant, $virement->initiateur));
        }

        $montant = format_number_french($virement->montant).' '.$virement->initiateur->pays->symbole_monnaie;

        $message = $request->action == 'virer' ? "Votre virement avec le compte bancaire numéro {$virement->compte_bancaire->num_compte_bancaire} de la banque {$virement->compte_bancaire->nom_banque} du montant {$montant} a été accepté par votre banque." : "Votre virement avec le compte bancaire numéro {$virement->compte_bancaire->num_compte_bancaire} de la banque {$virement->compte_bancaire->nom_banque} du montant {$montant} a été refusé par votre banque.";

        $virement->message = $message;

        Mail::to($virement->initiateur->email)->send(new MailVirement($virement));

        return redirect()->back()->with('message', "Le virement a été {$request->action} avec succès");
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Models\Virement  $virement
    * @return \Illuminate\Http\Response
    */
    public function destroy(Virement $virement)
    {
        //
    }
}
