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


    public function updateUserData(array $userData)
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

            $this->sendEmail($user);

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

    private function sendEmail($user): void
    {   
        
        $url = $this->buildUrl($user->token);

        $response = Mail::to($user->email)->send(new VerifyEmail($user->username, $url));

        if(!$response) throw new Exception("Falha ao enviar email de verificação");
    }

    private function buildUrl($token): string
    {
        $queryString = http_build_query(['token' => $token]);
        $baseUrl = 'http://localhost:8080/verify_email';
        return $baseUrl . '?' . $queryString;
    }
}