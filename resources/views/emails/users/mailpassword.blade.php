@component('mail::message')
<img src="http://tchadinfos.com/wp-content/uploads/2017/03/Capture-d%E2%80%99e%CC%81cran-2016-12-30-a%CC%80-17.55.05.png" width="400">
# Bonjour ! , {{ $user->nom }} <br>
Vous avez un nouveau Compte Cotontchad <br><br>
Votre mot de passe : <b>{{ $password}}</b>
<br>
Merci,<br>
{{ config('app.name') }}
@endcomponent
