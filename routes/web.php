<?php

use App\Http\Controllers\BinController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing.index');
});

Route::get('/bins', [BinController::class, 'index']);
