<?php

namespace App\Services;

use App\Models\User;
use App\Utils\Response;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

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

        error_log($user->password);
        error_log($password);

        if(!password_verify($password, $user->password)) throw new Exception("Email ou senha são inválidos");

        Auth::login($user);

        session()->regenerate();
    }
}