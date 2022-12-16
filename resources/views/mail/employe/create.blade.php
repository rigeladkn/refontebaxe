@component('mail::message')
# Bonjour,

Votre compte vient d'être créé.

Vous recevez ce mail pour vous fournir vos informations de connexion.

<p><strong>Email: {{ $employe->email }}</strong></p>
<p><strong>Mot de passe: {{ $employe->mot_de_passe }}</strong></p>

@component('mail::button', ['url' => route('login') ])
Vous pouvez cliquer ici pour vous connectez.
@endcomponent

Cordialement,<br>
{{ config('app.name') }}
@endcomponent
