<?php

use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     \Log::info('Channel authorization attempt', [
//         'user_id' => $user->id,
//         'requested_channel_id' => $id
//     ]);
//     return (int) $user->id === (int) $id;
//     // return true;
// });
Broadcast::channel('owner-notifications', function ($user) {
    \Log::info('Owner Channel Authorization', [
        'user_id' => $user->id,
        'role_names' => $user->role_names,
        'is_owner' => in_array('owner', $user->role_names)
    ]);
    return in_array('owner', $user->role_names);
});


