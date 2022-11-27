@extends('layouts.app')

@section('title',"Statut d'envoi")


@section('content')
<div id="content" class="py-4 mt-4">
    <div class="container">
      <div class="row">
        <div class="col-md-8 col-lg-6 col-xl-5 mx-auto">
          <!-- Send Money Success
          ============================================= -->
		  <div class="bg-light shadow-sm rounded p-3 p-sm-4 mb-4">
          <div class="text-center my-3">
          <p class="text-center text-success text-20 line-height-07"><i class="fas fa-check-circle"></i></p>
          <p class="text-center text-success text-8 line-height-07">Succès!</p>
          <p class="text-center text-4">Transaction effectuée</p>
          </div>
          <p class="text-center text-3 mb-4">Vous avez envoyé <span class="text-4 font-weight-500">$1000</span> au <span class="font-weight-500">9896896</span></p>
            <a href="{{route('home')}}" class="btn btn-primary btn-block">Retour à l'accueil</a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection