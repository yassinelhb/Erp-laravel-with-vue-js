@component('mail::message')
<img src="http://tchadinfos.com/wp-content/uploads/2017/03/Capture-d%E2%80%99e%CC%81cran-2016-12-30-a%CC%80-17.55.05.png" width="400">
# Bonjour ! , {{ $user->nom }} <br>
Vous avez demandé à rénilialiser votre mot de passe Cotontchad <br><br>
Vous pouvez également saisir le code de réinitialisation du mot de passe : <b>{{ $code}}</b>
<br>
Merci,<br>
{{ config('app.name') }}
@endcomponent
