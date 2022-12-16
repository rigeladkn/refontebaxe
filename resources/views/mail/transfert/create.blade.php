@if ($params['user_from'])
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="col-md-12 d-flex justify-content-center">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-2 mt-3"><img src="{{ asset('assets/img/logo_money_blanc.jpeg') }}" width="120" alt="logo {{ env('APP_NAME') }}"></div>
                        <label class="col-4 align-self-end text-left" for=""> Date : {{ $transfert->created_at->format('d/m/Y à H:i') }}</label>
                        <div class="col-1"></div>
                        <label class="col align-self-end text-left fw-bold">Numéro de facture: {{ $transfert->reference }}</label>
                    </div>

                    <div class="col mt-3">
                        <label><b>{{ env('APP_NAME') }}</b></label> <br>
                        <label for="">Adresse : 98 Rue des Orteaux</label><br>
                        <label for="">Code Postal et Ville : 75020 Paris</label><br>
                        <label for="">Numéro de téléphone : (+33)0605939008</label><br>
                        <label for="">Email : contact@baxe-moneytransfert.com</label><br>
                    </div>
                    <div class="row">
                        <div class="col-4"></div>
                        <div class="col-3"></div>
                        <div class="col-5 align-self-end text-left">
                            <label><b color="blue">{{ env('APP_NAME') }}</b></label> <br>
                            <label>Adresse : 98 Rue des Orteaux</label><br>
                            <label>Code Postal et Ville : 75020 Paris</label><br>
                            <label>Numéro de téléphone : (+33)0605939008</label><br>
                        </div>
                    </div>

                    <div><b>Objet :</b> Transfert d’argent</div>
                    <div class="table-responsive mt-4">
                        <table class="table table-bordered text-center">
                            <tr>
                                <td class="text-black fw-bold">Mode de remise</td>
                                <td>Portefeuille Mobile</td>
                                <td>{{ $user_to->pays->nom }}</td>
                            </tr>
                            <tr class="table-secondary">
                                <td class="text-black fw-bold">Mode de paiement</td>
                                <td colspan="2">{{ array_key_exists('transfert_mode', $params) ? $params['transfert_mode'] : 'Mode inconnu' }}</td>
                            </tr>
                            <tr>
                                <td class="text-black fw-bold">Argent disponible</td>
                                <td class=" text-center" colspan="2">En quelques minutes</td>
                            </tr>
                            <tr class="table-secondary">
                                <td class="text-black fw-bold">Montant du transfert</td>
                                <td class="text-black fw-bold text-right" colspan="2">{{ format_number_french($transfert->montant).' '.$user_from->pays->symbole_monnaie }}</td>
                            </tr>
                            <tr>
                                <td class="text-black fw-bold">Frais d’envoi</td>
                                <td class="text-black fw-bold text-right" colspan="2">+ {{ format_number_french($transfert->frais) }}</td>
                            </tr>
                            <tr class="table-secondary">
                                <td class="text-primary fw-bold">Total du transfert</td>
                                <td class="text-primary fw-bold text-right" colspan="2">{{ format_number_french($transfert->montant + $transfert->frais).' '.$user_from->pays->symbole_monnaie }}</td>
                            </tr>
                            <tr>
                                <td class="text-black fw-bold">Le bénéficiaire recevra</td>
                                <td class="text-black fw-bold  text-right" colspan="2">{{ array_key_exists('montant_recu', $params) ? format_number_french($params['montant_recu']).' '.$user_to->pays->symbole_monnaie : 'Montant inconnu' }}</td>
                            </tr>
                            <tr>
                                <td class="text-black fw-bold">Taux de change</td>
                                <td class="text-black fw-bold text-center">{{ $transfert->taux_from.' '.$user_from->pays->symbole_monnaie }} =</td>
                                <td class="text-black fw-bold  text-center">{{ $transfert->taux_to.' '.$user_to->pays->symbole_monnaie }}</td>
                            </tr>
                        </table>

                    </div>

                    <small>
                        La loi n°92/1442 du 31 décembre 1992 nous fait l’obligation de vous indiquer que le non-respect des conditions de paiement entraine des intérêts de retard suivant modalités et taux défini par la loi. Une indemnité forfaitaire de 40€ sera due pour frais de recouvrement en cas de retard de paiement.
                    </small>
                    <div class="mt-3 mb-3 fw-bold text-center">
                        <label for=""> {{ env('APP_NAME') }} | 98 RUE DES ORTEAUX 75020 PARIS</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
@endif

@if ($params['user_to'])
@component('mail::message')
# Bonjour

<p>
    {!! $message !!}
</p>

<div>
    <label><b>{{ env('APP_NAME') }}</b></label> <br>
    <label for="">Adresse : 98 Rue des Orteaux</label><br>
    <label for="">Code Postal et Ville : 75020 Paris</label><br>
    <label for="">Numéro de téléphone : (+33)0605939008</label><br>
    <label for="">Email : contact@lisocash.com</label><br>
</div>

<br>

<p>La loi n°92/1442 du 31 décembre 1992 nous fait l’obligation de vous indiquer que le non-respect des conditions de paiement entraine des intérêts de retard suivant modalités et taux défini par la loi. Une indemnité forfaitaire de 40€ sera due pour frais de recouvrement en cas de retard de paiement.
</p>
@endcomponent

@endif
