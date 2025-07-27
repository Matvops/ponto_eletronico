<?php

use App\Http\Controllers\AuthController;
use App\Livewire\Auth\Login;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function() {
    Route::get('/login', Login::class)->name('login');
    Route::post('/login', [AuthController::class, 'authentication'])->name('authentication');
});
