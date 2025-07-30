<?php

namespace App\Services;

use App\Mail\ConfirmationCode;
use App\Models\User;
use App\Utils\Response;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AuthService {
    
    public function authentication($dados): Response
    {
        try {

            extract($dados);
            $this->authenticate($email, $password);

            DB::commit();
            return Response::getResponse(true);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::getResponse(false, message: $e->getMessage());
        }
    }

    private function authenticate($email, $password) {
        $user = User::where('email', $email)
                        ->whereNull('deleted_at')
                        ->first();

        if(!$user) throw new Exception("Usuário não encontrado");

        if(!$user->email_verified_at) throw new Exception("Verifique seu cadastro no email");

        if(!password_verify($password, $user->password)) throw new Exception("Email ou senha são inválidos");

        Auth::login($user);

        session()->regenerate();
    }

    public function sendEmailConfirmation($email): Response
    {

        try {
            DB::beginTransaction();

            $user = User::where('email', $email)
                        ->whereNull('deleted_at')
                        ->first();

            if(!$user->email_verified_at) throw new Exception("Verifique seu cadastro no email");

            $user->confirmation_code = $this->generateRandomConfirmationCode();
            $user->save();    
            
            $this->send($user);

            DB::commit();
            return Response::getResponse(true, data: $user->email);
        } catch (Exception $e) {
            DB::rollBack();
            error_log($e->getMessage());
            return Response::getResponse(false, $e->getMessage());
        }
    }

    private function generateRandomConfirmationCode(): int
    {
        $random = rand(1, 9999);

        $code = str_pad(strval($random), 4, '0', STR_PAD_RIGHT);

        return intval($code);
    }

    private function send($user) {
        $response =  Mail::to($user->email)->send(new ConfirmationCode($user->confirmation_code, $user->username));

        if(!$response) throw new Exception("Erro no envio para email de confirmação");
    }
}