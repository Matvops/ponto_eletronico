<?php 

namespace App\Services;

use App\Factories\UserFactory;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Utils\Response;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserService {

    private UserRepository $userRepository;
    private EmailService $emailService;

    public function __construct(UserRepository $userRepository, EmailService $emailService)
    {
        $this->userRepository = $userRepository;
        $this->emailService = $emailService;
    }

    public function updateUserData(array $userData): Response
    {
        try {

            DB::beginTransaction();

            $emailExists = $this->userRepository->emailExists($userData['email']);
            
            $user = $this->userRepository->getByEmail(Auth::user()->email);

            if($this->invalidEmail($userData['email'], $user->email, $emailExists)) throw new Exception("Email inválido");

            if($this->invalidPassword($userData['password'], $user->password)) throw new Exception("Senha incorreta!");
            
            $response = $this->update($user, $userData);

            DB::commit();
            return Response::getResponse(true, $response['message'], $response['emailAlterado']);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::getResponse(false, $e->getMessage());
        }
    }

    private function invalidEmail($newEmail, $actualEmail, $emailExists): bool
    {
        return $newEmail != $actualEmail && $emailExists;
    }

    private function invalidPassword($newPassword, $actualPassword): bool
    {
        return !password_verify($newPassword, $actualPassword);
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

            $this->emailService->setMailStructure($user->email, new VerifyEmail($user->username));
            $this->emailService->sendWithPathParams($path, $pathParams);

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

            $user = UserFactory::create($userData);
            $user->save();

            $path = '/verify_email';
            $pathParams = ['token' => $user->token];

            $this->emailService->setMailStructure($user->email, new VerifyEmail($user->username));
            $this->emailService->sendWithPathParams($path, $pathParams);

            $message = "Email de confirmação enviado para $user->email! Verifique a caixa de mensagens.";

            DB::commit();
            return Response::getResponse(true, $message);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::getResponse(false, $e->getMessage());
        }
    }

    public function delete($id): Response
    {
        try {
            DB::beginTransaction();

            $user = User::where('usr_id', Crypt::decrypt($id))
                ->whereNull('deleted_at')
                ->first();

            if(!$user) throw new Exception();

            $user->delete();

            DB::commit();
            return Response::getResponse(true, "Usuário $user->username deletado");
        } catch(Exception $e) {
            DB::rollBack();
            return Response::getResponse(false, "Erro ao deletar usuário");
        }
    }

    public function updateByAdminView($userData) 
    {
        try {
            DB::beginTransaction();

            $emailExists = User::withTrashed()
                                ->where('email', $userData['email'])
                                ->exists();

            $user = User::where('usr_id', intval($userData['usr_id']))->first();

            if($this->invalidEmail($userData['email'], $user->email, $emailExists)) throw new Exception("Email inválido");
            
            $this->update($user, $userData);
            
            if($userData['reset_time_balance']) 
                TimeSheetService::updateTimeSheetStatus($user->usr_id);
            
            DB::commit();
            return Response::getResponse(true, "Dados atualizados com sucesso!");
        } catch(Exception $e) {
            DB::rollBack();
            return Response::getResponse(false, $e->getMessage());
        }
    }

}