<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('Online', function ($user) {
    return $user;
});

Broadcast::channel('Chat', function () {
    return true;
});
Broadcast::channel('ReadMessage', function () {
    return true;
});

Broadcast::channel('Besoin', function () {
    return true;
});
