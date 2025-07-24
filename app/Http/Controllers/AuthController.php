<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AuthController extends Controller
{
    

    public function login(): View
    {
        return view('auth.login');
    }
}
