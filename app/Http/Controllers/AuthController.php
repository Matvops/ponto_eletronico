<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AuthenticationRequest;
use App\Http\Requests\Auth\ConfirmCodeRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

    public function confirmCode(ConfirmCodeRequest $request): View|RedirectResponse
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

        return view('livewire.new-password', $response->getData());
    }
}
