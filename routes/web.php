<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/{page}', 'AdminController@index');
Route::get('/{page}', [AdminController::class, 'index']);
