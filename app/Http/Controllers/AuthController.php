<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AuthController extends Controller
{

    public function authentication()
    {
        return redirect()->back();
    }
}
