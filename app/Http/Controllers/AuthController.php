<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AuthenticationRequest;
use App\Http\Requests\Auth\ConfirmCodeRequest;
use App\Http\Requests\Auth\StoreNewPasswordRequest;
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

        return redirect()->route('home_admin');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function sendEmailConfirmation($email) {
        $response = $this->service->sendEmailConfirmation($email);

        if($response) {
            return back()
                    ->withInput()
                    ->with('error_send_email', $response->getMessage());
        }
    }

    public function confirmCode(ConfirmCodeRequest $request): RedirectResponse
    {
        $dados = [
            'email' => $request->input('email'),
            'numbers' => [
                $request->input('numberOne'),
                $request->input('numberTwo'),
                $request->input('numberTree'),
                $request->input('numberFour'),
            ]
        ];

        $response = $this->service->confirmCode($dados);

        if(!$response->getStatus()) {
            return back()
                    ->withInput()
                    ->with('error_confirm_code', $response->getMessage());
        }

        return redirect()->route('new_password', $response->getData());
    }

    public function storeNewPassword(StoreNewPasswordRequest $request): RedirectResponse
    {
        $dados = [
            'token' => $request->input('token'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation'),
        ];

        $response = $this->service->storeNewPassword($dados);

        if(!$response->getStatus()) {
            return back()
                    ->withInput()
                    ->with('error_store_new_password', $response->getMessage());
        }

        return redirect()->route('login')->with('success_store_new_password', true);
    }
}
