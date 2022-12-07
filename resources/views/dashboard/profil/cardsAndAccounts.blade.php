@extends('layouts.app')

@section('second-menu')
    <!-- Secondary Menu
                            ============================================= -->
    <div class="bg-primary">
        <div class="container d-flex justify-content-center">
            <ul class="nav secondary-nav">
                <li class="nav-item"> <a class="nav-link " href="{{ route('profile') }}">Mon compte</a></li>
                <li class="nav-item"> <a class="nav-link active" href="{{ route('cardsAndAccounts') }}">Cartes et comptes
                        bancaires</a>
                </li>
                {{-- <li class="nav-item"> <a class="nav-link" href="profile-notifications.html">Notifications</a></li> --}}
            </ul>
        </div>
    </div>
    <!-- Secondary Menu end -->
@endsection


@section('content')
    <div class="container mt-5 mb-5">
        <div class="row">
            <!-- Left Panel
                              ============================================= -->
            <aside class="col-lg-3">


                <div class="bg-light shadow-sm rounded text-center p-3 mb-4">
                    <div class="text-17 text-light my-3"><i class="fas fa-comments"></i></div>
                    <h3 class="text-3 font-weight-400 my-4">Besoin d'aide ?</h3>
                    {{-- <p class="text-muted opacity-8 mb-4">Désirez-vous modifier plus d'attributs sur votre profil ?</p> --}}
                    <a href="#" class="btn btn-primary btn-block btn-sm">Nous contacter</a>
                </div>
                <!-- Need Help? End -->

            </aside>
            <!-- Left Panel End -->

            <!-- Middle Panel
                              ============================================= -->
            <div class="col-lg-9">

                <!-- Credit or Debit Cards
                                ============================================= -->
                <div class="bg-light shadow-sm rounded p-4 mb-4">
                    <h3 class="text-5 font-weight-400 mb-4">Carte de débit <span class="text-muted text-4">(pour les
                            transactions)</span></h3>
                    <div class="row">
                        @if (!count($paymentMethods))
                            <div class="mx-5 my-4">Aucune carte ajoutée</div>
                        @else
                            @foreach ($paymentMethods as $paymentMeth)
                                @if ($paymentMeth['type'] == 'visa')
                                    <div class="col-12 col-sm-6 col-lg-4 mb-4">
                                        <div class="account-card account-card-primary text-white rounded p-3 mb-4 mb-lg-0">
                                            <p class="text-4"> XXXX-XXXX-XXXX-{{ substr($paymentMeth['number'], -4) }}</p>
                                            <p class="d-flex align-items-center"> <span
                                                    class="account-card-expire text-uppercase d-inline-block opacity-6 mr-2">Valid<br>
                                                    thru<br>
                                                </span> <span class="text-4 opacity-9">
                                                    {{ substr($paymentMeth['expirationDate'], 5, 2) . '/' . substr($paymentMeth['expirationDate'], 2, 2) }}
                                                </span>
                                                <span
                                                    class="bg-light text-0 text-body font-weight-500 rounded-pill d-inline-block px-2 line-height-4 opacity-8 ml-auto">
                                                    @if ($paymentMeth['status'])
                                                        Valide
                                                    @else
                                                        Non valide
                                                    @endif
                                                </span>
                                            </p>
                                            <p class="d-flex align-items-center m-0"> <span
                                                    class="text-uppercase font-weight-500">{{ $paymentMeth['holder'] }}</span>
                                                <img class="ml-auto" src="images/payment/visa.png" alt="visa"
                                                    title="">
                                            </p>
                                            <div class="account-card-overlay rounded">
                                                {{-- <a href="#"
                                                    data-target="#edit-card-details" data-toggle="modal"
                                                    class="text-light btn-link mx-2"><span class="mr-1"><i
                                                            class="fas fa-edit"></i></span>Modifier</a>  --}}

                                                <form action="{{ route('deletePaymentMethod') }}" method="post"
                                                    id="deleteCard{{ $paymentMeth['id'] }}">
                                                    @csrf
                                                    <input hidden type="text" value="{{ $paymentMeth['id'] }}"
                                                        name="paymentMethId">
                                                    <a class="btn text-light btn-link mx-2"
                                                        onclick="document.getElementById('deleteCard{{ $paymentMeth['id'] }}').submit()">
                                                        <span class="mr-1"><i class="fas fa-minus-circle"></i>
                                                        </span>Supprimer
                                                    </a>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-12 col-sm-6 col-lg-4">
                                        <div class="account-card text-white rounded p-3 mb-4 mb-lg-0">
                                            <p class="text-4">XXXX-XXXX-XXXX-{{ substr($paymentMeth['number'], -4) }}</p>
                                            <p class="d-flex align-items-center"> <span
                                                    class="account-card-expire text-uppercase d-inline-block opacity-6 mr-2">Valid<br>
                                                    thru<br>
                                                </span> <span class="text-4 opacity-9">
                                                    {{ substr($paymentMeth['expirationDate'], 5, 2) . '/' . substr($paymentMeth['expirationDate'], 2, 2) }}

                                                </span>

                                                <span
                                                    class="bg-light text-0 text-body font-weight-500 rounded-pill d-inline-block px-2 line-height-4 opacity-8 ml-auto">
                                                    @if ($paymentMeth['status'])
                                                        Valide
                                                    @else
                                                        Non valide
                                                    @endif
                                                </span>
                                            </p>
                                            <p class="d-flex align-items-center m-0"> <span
                                                    class="text-uppercase font-weight-500">{{ $paymentMeth['holder'] }}</span>
                                                <img class="ml-auto" src="images/payment/mastercard.png" alt="mastercard"
                                                    title="">
                                            </p>
                                            <div class="account-card-overlay rounded">
                                                {{-- <a href="#"
                                                    data-target="#edit-card-details" data-toggle="modal"
                                                    class="text-light btn-link mx-2"><span class="mr-1"><i
                                                            class="fas fa-edit"></i></span>Modifier</a>  --}}
                                                <form action="{{ route('deletePaymentMethod') }}" method="post"
                                                    id="deleteCard{{ $paymentMeth['id'] }}">
                                                    @csrf
                                                    <input hidden type="text" value="{{ $paymentMeth['id'] }}"
                                                        name="paymentMethId">
                                                    <a class="btn text-light btn-link mx-2"
                                                        onclick="document.getElementById('deleteCard{{ $paymentMeth['id'] }}').submit()">
                                                        <span class="mr-1"><i class="fas fa-minus-circle"></i>
                                                        </span>Supprimer
                                                    </a>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif



                        <div class="col-12 col-sm-6 col-lg-4"> <a href="" data-target="#add-new-card-details"
                                data-toggle="modal"
                                class="account-card-new d-flex align-items-center rounded h-100 p-3 mb-4 mb-lg-0">
                                <p class="w-100 text-center line-height-4 m-0"> <span class="text-3"><i
                                            class="fas fa-plus-circle"></i></span> <span
                                        class="d-block text-body text-3">Ajouter une nouvelle carte</span> </p>
                            </a> </div>
                    </div>
                </div>
                <!-- Edit Card Details Modal
                                ================================== -->
                <div id="edit-card-details" class="modal fade" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title font-weight-400">Mise à jour de la carte</h5>
                                <button type="button" class="close font-weight-400" data-dismiss="modal"
                                    aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                            </div>
                            <div class="modal-body p-4">
                                <form id="updateCard" method="post">
                                    <div class="form-group">
                                        <label for="edircardNumber">Numéro de la carte</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend"> <span class="input-group-text"><img
                                                        class="ml-auto" src="images/payment/visa.png" alt="visa"
                                                        title=""></span> </div>
                                            <input type="text" class="form-control" data-bv-field="edircardNumber"
                                                id="edircardNumber" disabled value="XXXXXXXXXXXX4151"
                                                placeholder="Numéro de la carte" name="cardNumber">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="editexpiryDate">Date d'expiration</label>
                                                <input id="editexpiryDate" type="text" class="form-control"
                                                    data-bv-field="editexpiryDate" required value="07/24"
                                                    placeholder="MM/YY" name="expiryDate">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="editcvvNumber">CVV <span class="text-info ml-1"
                                                        data-toggle="tooltip"
                                                        data-original-title="Pour les cartes Visa/Mastercard, le CVV  est affiché sur  printed on the signature panel on the back of the card immediately after the card's account number. For American Express, the four-digit CVV number is printed on the front of the card above the card account number."><i
                                                            class="fas fa-question-circle"></i></span></label>
                                                <input id="editcvvNumber" type="password" class="form-control"
                                                    data-bv-field="editcvvNumber" required value="321"
                                                    placeholder="CVV (3 caractères)" name="cvv">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="editcardHolderName">Titulaire de la carte</label>
                                        <input type="text" class="form-control" data-bv-field="editcardHolderName"
                                            id="editcardHolderName" required value="Smith Rhodes"
                                            placeholder="Nom du titulaire (Lisible sur la carte)" name="holder">
                                    </div>
                                    <button class="btn btn-primary btn-block" type="submit">Mettre à jour</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add New Card Details Modal
                                ================================== -->
                <div id="add-new-card-details" class="modal fade" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title font-weight-400">Ajouter une carte</h5>
                                <button type="button" class="close font-weight-400" data-dismiss="modal"
                                    aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                            </div>
                            <div class="modal-body p-4">
                                <form id="addCard" method="post" action="{{ route('addPaymentCard') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="type">Type de la carte</label>
                                        <select id="type" class="custom-select" required="" name="type">
                                            {{-- <option value="" disabled>Type</option> --}}
                                            <option value="visa">Visa</option>
                                            <option value="mastercard">MasterCard</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="number">Numéro de la carte</label>
                                        <input type="text" class="form-control" data-bv-field="number" id="number"
                                            required value="" placeholder="Numéro de la carte" name="number">
                                    </div>
                                    <div class="form-row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="expirationDate">Date d'expiration</label>
                                                <input id="expirationDate" type="text" class="form-control"
                                                    data-bv-field="expirationDate" required value=""
                                                    placeholder="MM/YY" name="expirationDate">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="CVV">CVV <span class="text-info ml-1"
                                                        data-toggle="tooltip"
                                                        data-original-title="For Visa/Mastercard, the three-digit CVV number is printed on the signature panel on the back of the card immediately after the card's account number. For American Express, the four-digit CVV number is printed on the front of the card above the card account number."><i
                                                            class="fas fa-question-circle"></i></span></label>
                                                <input name="CVV" type="text" class="form-control"
                                                    data-bv-field="CVV" required value=""
                                                    placeholder="CVV (3 digits)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="holder">Titulaire de la carte</label>
                                        <input type="text" class="form-control" data-bv-field="holder" name="holder"
                                            required value="" placeholder="Titulaire de la carte">
                                    </div>
                                    <button class="btn btn-primary btn-block" type="submit">Ajouter la carte</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Credit or Debit Cards End -->

                <!-- Bank Accounts
                                ============================================= -->
                <div class="bg-light shadow-sm rounded p-4 mb-4">
                    <h3 class="text-5 font-weight-400 mb-4">Comptes bancaires <span class="text-muted text-4">(pour les
                            retraits)</span></h3>
                    <div class="row col-12">
                        @if (!count($bankAccounts))
                            <div class="mt-4 mx-3">Aucun compte bancaire ajouté</div>
                        @else
                            @foreach ($bankAccounts as $bankAccount)
                                <div class="col-lg-6 col-sm-12">
                                    <div class="account-card account-card-primary text-white rounded mb-4 mb-lg-0">
                                        <div class="row no-gutters">
                                            <div class="col-3 d-flex justify-content-center p-3">
                                                <div class="my-auto text-center text-13"> <i
                                                        class="fas fa-university"></i>
                                                    <p
                                                        class="bg-light text-0 text-body font-weight-500 rounded-pill d-inline-block px-2 line-height-4 opacity-8 mb-0">
                                                        Banque</p>
                                                </div>
                                            </div>
                                            <div class="col-9 border-left">
                                                <div class="py-4 my-2 pl-4">
                                                    <p class="text-4 font-weight-500 mb-1">{{ $bankAccount['bank_name'] }}
                                                    </p>
                                                    <p class="text-4 opacity-9 mb-1">
                                                        XXXXXXXXXXXX-{{ substr($bankAccount['number'], -4) }}</p>
                                                    <p class="m-0">Approuvée <span class="text-3"><i
                                                                class="fas fa-check-circle"></i></span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="account-card-overlay rounded">
                                           {{-- <a href="#"
                                                data-target="#bank-account-details" data-toggle="modal"
                                                class="text-light btn-link mx-2"><span class="mr-1"><i
                                                        class="fas fa-share"></i></span>Plus de détails</a> --}}
                                            <form action="{{ route('deleteBankAccount') }}" method="post"
                                                id="deleteBankAccount{{ $bankAccount['id'] }}">
                                                @csrf
                                                <input hidden type="text" value="{{ $paymentMeth['id'] }}"
                                                    name="accountId">
                                                <a class="btn text-light btn-link mx-2"
                                                    onclick="document.getElementById('deleteBankAccount{{ $bankAccount['id'] }}').submit()">
                                                    <span class="mr-1"><i class="fas fa-minus-circle"></i>
                                                    </span>Supprimer
                                                </a>
                                            </form>
                                        </div>
                                    </div>
                            </div>
                                    @endforeach
                        @endif

                        <div class="col-lg-6 col-sm-12"> <a href="" data-target="#add-new-bank-account"
                                data-toggle="modal"
                                class="account-card-new d-flex align-items-center rounded h-100 p-3 mb-4 mb-lg-0">
                                <p class="w-100 text-center line-height-4 m-0"> <span class="text-3"><i
                                            class="fas fa-plus-circle"></i></span> <span
                                        class="d-block text-body text-3">Ajouter une nouveau compte</span> </p>
                            </a> </div>
                    </div>
                </div>
                <!-- Edit Bank Account Details Modal
                                ======================================== -->
                <div id="bank-account-details" class="modal fade" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered transaction-details" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="row no-gutters">
                                    <div class="col-sm-5 d-flex justify-content-center bg-primary rounded-left py-4">
                                        <div class="my-auto text-center">
                                            <div class="text-17 text-white mb-3"><i class="fas fa-university"></i></div>
                                            <h3 class="text-6 text-white my-3">HDFC Bank</h3>
                                            <div class="text-4 text-white my-4">XXX-9027 | INR</div>
                                            <p
                                                class="bg-light text-0 text-body font-weight-500 rounded-pill d-inline-block px-2 line-height-4 mb-0">
                                                Primary</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-7">
                                        <h5 class="text-5 font-weight-400 m-3">Bank Account Details
                                            <button type="button" class="close font-weight-400" data-dismiss="modal"
                                                aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                                        </h5>
                                        <hr>
                                        <div class="px-3">
                                            <ul class="list-unstyled">
                                                <li class="font-weight-500">Account Type:</li>
                                                <li class="text-muted">Personal</li>
                                            </ul>
                                            <ul class="list-unstyled">
                                                <li class="font-weight-500">Account Name:</li>
                                                <li class="text-muted">Smith Rhodes</li>
                                            </ul>
                                            <ul class="list-unstyled">
                                                <li class="font-weight-500">Account Number:</li>
                                                <li class="text-muted">XXXXXXXXXXXX-9025</li>
                                            </ul>
                                            <ul class="list-unstyled">
                                                <li class="font-weight-500">Bank Country:</li>
                                                <li class="text-muted">India</li>
                                            </ul>
                                            <ul class="list-unstyled">
                                                <li class="font-weight-500">Status:</li>
                                                <li class="text-muted">Approved <span class="text-success text-3"><i
                                                            class="fas fa-check-circle"></i></span></li>
                                            </ul>
                                            <p><a href="#"
                                                    class="btn btn-sm btn-outline-danger btn-block shadow-none"><span
                                                        class="mr-1"><i class="fas fa-minus-circle"></i></span>Delete
                                                    Account</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add New Bank Account Details Modal
                                ======================================== -->
                <div id="add-new-bank-account" class="modal fade" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title font-weight-400">Ajouter un compte bancaire</h5>
                                <button type="button" class="close font-weight-400" data-dismiss="modal"
                                    aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
                            </div>
                            <div class="modal-body p-4">
                                <form id="addbankaccount" method="post" action="{{ route('addBankAccount') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="bank_name">Nom de la banque</label>
                                        <select class="custom-select" id="bank_name" name="bank_name">
                                            <option value="UBA">UBA</option>
                                            <option value="Ecobank">Ecobank</option>
                                            <option value="BOA">BOA</option>
                                            <option value="BOAD">BOAD</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="number">Numéro de compte</label>
                                        <input type="text" class="form-control" data-bv-field="number" name="number"
                                            required value="" placeholder="e.g. 12346678900001">
                                    </div>
                                    <div class="form-check custom-control custom-checkbox mb-3">
                                        <input id="remember-me" name="remember" class="custom-control-input"
                                            type="checkbox" required>
                                        <label class="custom-control-label" for="remember-me">Je confirme les données
                                            ci-dessus</label>
                                    </div>
                                    <button class="btn btn-primary btn-block" type="submit">Ajouter le compte</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Bank Accounts End -->

            </div>
            <!-- Middle Panel End -->
        </div>
    </div>
@endsection
