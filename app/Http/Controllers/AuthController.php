<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AuthenticationRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    private AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    public function authentication(AuthenticationRequest $request): RedirectResponse
    {

        $dados = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        $response = $this->service->authentication($dados);


        if(!$response->getStatus()) {
            return back()
                    ->withInput()
                    ->with('error_auth', $response->getMessage());
        }

        dd(Auth::user());
    }
}
