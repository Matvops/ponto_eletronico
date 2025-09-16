<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TimeSheetController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdminMiddleware;
use App\Http\Middleware\IsUserMiddleware;
use App\Livewire\AdminUpdateProfile;
use App\Livewire\Auth\Login;
use App\Livewire\ClockInClockOut;
use App\Livewire\ConfirmationCode;
use App\Livewire\ForgotPassword;
use App\Livewire\HomeAdmin;
use App\Livewire\HomeUser;
use App\Livewire\NewPassword;
use App\Livewire\RegisterUser;
use App\Livewire\UpdateProfile;
use App\Livewire\ViewDays;
use App\Livewire\ViewUsers;
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
    Route::get('/verify_email/{token}', [AuthController::class, 'verifyEmail'])->name('verify_email');
    Route::fallback(function() {
        return redirect()->route('login');
    });
});

Route::middleware('auth')->group(function() {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::middleware(IsAdminMiddleware::class)->get('/', HomeAdmin::class)->name('home_admin');
    Route::get('/home', HomeUser::class)->name('home_user');
    Route::get('/update_profile', UpdateProfile::class)->name('update_profile');
    Route::post('/update_profile', [UserController::class, 'update'])->name('save_updated_profile');
    Route::get('/register_user', RegisterUser::class)->name('register_user');
    Route::middleware(IsAdminMiddleware::class)->post('/register_user', [UserController::class, 'register'])->name('register');
    Route::middleware(IsAdminMiddleware::class)->get('/view_users', ViewUsers::class)->name('view_users');
    Route::delete('/delete_user', [UserController::class, 'delete'])->name('delete_user');
    Route::middleware(IsUserMiddleware::class)->get('/view_time_sheets', ViewDays::class)->name('view_days');
    Route::middleware(IsUserMiddleware::class)->get('/clock_in_clock_out', ClockInClockOut::class)->name('clock_in_clock_out');
    Route::middleware(IsUserMiddleware::class)->post('/punch_clock', [TimeSheetController::class, 'punchClock'])->name('punch_clock');
    Route::middleware(IsAdminMiddleware::class)->get('/admin_update/{id}', AdminUpdateProfile::class)->name('admin_update_profile');
    Route::middleware(IsAdminMiddleware::class)->post('/admin_update', [UserController::class, 'updateUserByAdminView'])->name('update_profile_by_admin');
    Route::fallback(function() {
        return redirect()->route('logout');
    });
});
