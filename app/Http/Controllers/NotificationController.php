<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function readAllNotifications()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        return redirect('home');
    }
}
