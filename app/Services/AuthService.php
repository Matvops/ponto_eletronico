<?php

namespace App\Services;

use App\Mail\ConfirmationCode;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Utils\Functions;
use App\Utils\Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthService {
    
    public function __construct(
                                private EmailService $emailService,
                                private UserRepository $userRepository
                            )

    {
    }

    public function authentication($dados): Response
    {
        try {

            extract($dados);

            $user = $this->userRepository->getOnlyActiveUsersByEmail($email);

            if(!$user) throw new Exception("Email ou senha são inválidos");

            $this->authenticate($user, $password);

            $this->login($user);

            return Response::getResponse(true);
        } catch (Exception $e) {
            return Response::getResponse(false, message: $e->getMessage());
        }
    }

    private function authenticate(User $user, $password) 
    {

        if(!$user->email_verified_at) throw new Exception("Verifique seu email.");

        if(!password_verify($password, $user->password)) throw new Exception("Email ou senha são inválidos");
    }

    private function login(User $user): void
    {
        Auth::login($user);
        Functions::regenerateSession();
    }

    public function sendEmailConfirmation($email): Response
    {

        try {
            DB::beginTransaction();

            $user = $this->userRepository->getOnlyActiveUsersByEmail($email);

            if(!$user) throw new Exception('Erro ao enviar email');

            if(!$user->email_verified_at) throw new Exception("Verifique seu cadastro no email");

            $maxDigits = 4;
            $user->confirmation_code = Functions::generateRandomCode($maxDigits);
            $user->save();    

            $this->emailService->setMailStructure($user->email, new ConfirmationCode($user->confirmation_code, $user->username));
            $this->emailService->send();

            DB::commit();
            return Response::getResponse(true, data: $user->email);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::getResponse(false, $e->getMessage());
        }
    }

    public function confirmCode(array $dados): Response
    {
        try {
            DB::beginTransaction();
            
            $user = $this->userRepository->getOnlyActiveUsersByEmail($dados['email']);

            $code = Functions::concatenateNumbersInArrayToInt($dados['numbers']);
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