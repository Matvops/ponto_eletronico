<?php

namespace App\Services;

use App\Mail\ConfirmationCode;
use App\Models\User;
use App\Utils\Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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

        if(!$user->email_verified_at) throw new Exception("Verifique seu email.");

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

            $emailService = new EmailService($user, new ConfirmationCode($user->confirmation_code, $user->username));
            $emailService->send();

            DB::commit();
            return Response::getResponse(true, data: $user->email);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::getResponse(false, $e->getMessage());
        }
    }

    private function generateRandomConfirmationCode(): int
    {
        $random = rand(1, 9999);

        $code = str_pad(strval($random), 4, '0', STR_PAD_RIGHT);

        return intval($code);
    }

    public function confirmCode(array $dados): Response
    {
        try {
            DB::beginTransaction();
            
            $user = User::where('email', $dados['email'])
                            ->whereNull('deleted_at')
                            ->first();

            $code = $this->concatenateNumbers($dados['numbers']);

            if($user->confirmation_code !== $code) throw new Exception("Código inválido. Verifique seu email!"); 

            $user->token = Str::random(64);
            $user->confirmation_code = null;
            $user->save();

            DB::commit();
            return Response::getResponse(true, data: ['token' => Crypt::encrypt($user->token), 'email' => Crypt::encrypt($user->email)]);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::getResponse(false, $e->getMessage());
        }
    }

    private function concatenateNumbers(array $numbers): int
    {
        return intval($numbers[0] . $numbers[1] . $numbers[2] . $numbers[3]);
    }

    public function storeNewPassword(array $dados): Response
    {
        try {
            DB::beginTransaction();

            $token = Crypt::decrypt($dados['token']);
            $email = Crypt::decrypt($dados['email']);

            $user = User::where('email', $email)
                            ->whereNull('deleted_at')
                            ->first(); 
            
            if(!$user) throw new Exception('Email inválido');
            if($user->token !== $token) throw new Exception('Token inválido');

            $user->password = bcrypt($dados['password']);
            $user->token = null;
            $user->save();
            
            DB::commit();
            return Response::getResponse(true);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::getResponse(false, $e->getMessage());
        }
    }

    public function verifyEmail($token): Response
    {
        try {
            DB::beginTransaction();

            $user = User::where('token', $token)
                            ->whereNull('email_verified_at')
                            ->whereNull('deleted_at')
                            ->first();
            
            if(!$user) throw new Exception("Token inválido");

            $user->token = null;
            $user->email_verified_at = Carbon::now();
            $user->save();
            
            DB::commit();
            return Response::getResponse(true, "Email confirmado com sucesso!");
        } catch (Exception $e) {
            DB::rollBack();
            return Response::getResponse(false, $e->getMessage());
        }
    }
}