<?php 

namespace App\Services;

use App\Mail\VerifyEmail;
use App\Models\User;
use App\Utils\Response;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserService {

    public function updateUserData(array $userData): Response
    {
        try {

            DB::beginTransaction();

            $userExists = User::where('email', $userData['email'])->exists();

            $user = User::where('email', Auth::user()->email)->first();

            if($userData['email'] !=  $user->email && $userExists) throw new Exception("Email inválido");

            if(!password_verify($userData['password'], $user->password)) throw new Exception("Senha incorreta!");

            $response = $this->update($user, $userData);

            DB::commit();
            return Response::getResponse(true, $response['message'], $response['emailAlterado']);
        } catch (Exception $e) {
            DB::rollBack();
            error_log($e->getMessage());
            return Response::getResponse(false, $e->getMessage());
        }
    }

    private function update(User $user, array $newData)
    {
        $user->username = $newData['username'];
            
        if($newData['email'] != $user->email) {

            $user->token = Str::random(64);
            $user->email = $newData['email'];
            $user->email_verified_at = null;
            $user->save();

            $path = '/verify_email';
            $pathParams = ['token' => $user->token];

            $emailService = new EmailService($user, new VerifyEmail($user->username));
            $emailService->sendWithPathParams($path, $pathParams);

            $message = "Email de confirmação enviado! Verifique sua caixa de mensagens.";
            $emailAlterado = true;
        } else {
            
            $user->save();

            $message = "Seus dados foram atualizados!";
            $emailAlterado = false;
        }

        return [
            'message' => $message,
            'emailAlterado' => $emailAlterado
        ];
    }

    public function register(array $userData): Response
    {
        try {
            DB::beginTransaction();

            $user = new User();
            $user->username = $userData['username'];
            $user->email = $userData['email'];
            $user->password = bcrypt($userData['password']);
            $user->role = strtoupper($userData['role']);
            $user->email_verified_at = null;
            $user->token = Str::random(64);
            $user->confirmation_code = null;
            $user->save();

            $path = '/verify_email';
            $pathParams = ['token' => $user->token];

            $emailService = new EmailService($user, new VerifyEmail($user->username));
            $emailService->sendWithPathParams($path, $pathParams);
            $message = "Email de confirmação enviado para $user->email! Verifique a caixa de mensagens.";

            DB::commit();
            return Response::getResponse(true, $message);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::getResponse(false, $e->getMessage());
        }
    }
}