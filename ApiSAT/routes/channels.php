<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('pdf-process.{id}', function ($user, $id) {
    return (string)$user->id === (string) $id;
},['guards' => ['api']]);
