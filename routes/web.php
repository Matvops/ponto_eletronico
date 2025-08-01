<?php

use App\Http\Controllers\AuthController;
use App\Livewire\Auth\Login;
use App\Livewire\ConfirmationCode;
use App\Livewire\ForgotPassword;
use App\Livewire\NewPassword;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function() {
    Route::get('/login', Login::class)->name('login');
    Route::post('/login', [AuthController::class, 'authentication'])->name('authentication');
    Route::get('/forgot_password', ForgotPassword::class)->name('forgot_password');
    Route::post('/sendEmailConfirmation', [AuthController::class, 'sendEmailConfirmation'])->name('send_email_confirmation');
    Route::get('/confirm_code', ConfirmationCode::class)->name('confirm_code_view');
    Route::post('/confirm_code', [AuthController::class, 'confirmCode'])->name('confirm_code');
    Route::get('/new_password/{email}/{token}', NewPassword::class)->name('new_password');
    Route::post('/new_password', [AuthController::class, 'storeNewPassword'])->name('store_new_password');
});

Route::middleware('auth')->group(function() {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});
