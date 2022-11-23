@extends('layouts.app')

@section('content')
<div id="content" class="py-4">
    <div class="container">
      {{-- <h2 class="font-weight-400 text-center mt-3">Send Money</h2> --}}
      <p class="text-4 text-center mb-4">Vous envoyez de l'argent au <span class="font-weight-500">+33 45474787</span></p>
      <div class="row">
        <div class="col-md-8 col-lg-6 col-xl-5 mx-auto">
          <div class="bg-light shadow-sm rounded p-3 p-sm-4 mb-4"> 
            
            <!-- Send Money Confirm
            ============================================= -->
            <form id="form-send-money" method="post">
              <h3 class="text-5 font-weight-400 mb-3">Details</h3>
              <p class="mb-1">Montant à envoyer <span class="text-3 float-right">1,000.00 USD</span></p>
              <p class="mb-1">Frais de transaction <span class="text-3 float-right">7.21 USD</span></p>
              <hr>
              <p class="font-weight-500">Total<span class="text-3 float-right">1,007.21 USD</span></p>
              <a href="{{route("sendStatus")}}" class="btn btn-primary btn-block">Oui, j'envoie</a>
            </form>
            <!-- Send Money Confirm end --> 
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection