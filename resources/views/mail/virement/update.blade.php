@component('mail::message')
# Bonjour

<p>{{ $virement->message }}</p>

@if ($virement->getRawOriginal('statut') == 0)
<p>Voici les détails du refus du virement :</p>
<p>"{{ $virement->commentaire }}"</p>
@endif

Cordialement,<br>
{{ config('app.name') }}
@endcomponent
