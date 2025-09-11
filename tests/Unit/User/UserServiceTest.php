<?php

namespace Tests\Unit\User;

use App\Factories\UserFactory;
use App\Mail\VerifyEmail;
use App\Models\User;
use App\Repositories\TimeSheetRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\TimeSheetService;
use App\Services\UserService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\RunClassInSeparateProcess;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[RunClassInSeparateProcess]
class UserServiceTest extends TestCase {

    private UserService $userService;
    private UserRepository&Stub $userRepositoryStub;
    private TimeSheetRepository&Stub $timeSheetRepositoryMock;
    private EmailService&MockInterface $emailServiceMock;
    private $user;

    protected function setUp(): void
    {
        $this->userRepositoryStub = $this->createStub(UserRepository::class);
        $this->emailServiceMock = Mockery::mock(EmailService::class);
        $this->timeSheetRepositoryMock = $this->createStub(TimeSheetRepository::class);
        $this->userService = new UserService($this->userRepositoryStub, $this->emailServiceMock, $this->timeSheetRepositoryMock);

        $this->user = (object)[
                                'usr_id' => 4,
                                'username' => 'USER1',
                                'email' => 'user1@gmail.com',
                                'password' => '$2y$12$tYiw4TP.bVjUiHS4vFE2OOD.6.cp4jua1jxJHn9Am1XnoFZ1ocrEy',
                                'role' => 'USER',
                                'email_verified_at' => '2025-08-11 22:04:10.000',
                                'token' => null,
                                'created_at' => '2025-08-01 21:52:53.000',
                                'updated_at' => '2025-09-08 19:32:46.000',
                                'deleted_at' => null,
                                'confirmation_code' => null,
                            ];

        $dbMock = Mockery::mock('alias:' . DB::class);
        $dbMock->shouldIgnoreMissing();
        Auth::expects('user')->andReturn($this->user);
    }


    protected function tearDown(): void
    {
        Mockery::close();   
    }
    
    public function test_update_user_data_with_same_email_successfully(): void
    {
        $userData = [
            'username' => 'USER',
            'email' => 'user1@gmail.com',
            'password' => 'Senha123'
        ];

        $userMock = Mockery::mock(User::class);

        $userMock->shouldReceive('save')
                    ->once()
                    ->andReturnSelf();

        $this->makeGetAttributeUser('email', $userMock, $this->user->email);
        $this->makeGetAttributeUser('password', $userMock, $this->user->password);

        $this->makeSetAttributeUser('username', $userMock, $userData['username']);
        
        $this->userRepositoryStub->method('emailExists')
                                    ->with($userData['email'])
                                    ->willReturn(true);

        $this->userRepositoryStub->method('getByEmail')
                                    ->with($this->user->email)
                                    ->willReturn($userMock);
                    
        $response = $this->userService->updateUserData($userData); 

        $this->assertTrue($response->getStatus());
        $this->assertSame("Seus dados foram atualizados!", $response->getMessage());
        $this->assertFalse($response->getData());
    }

    public function test_update_user_data_with_different_email_successfully(): void
    {

        $token = 'abcdefg';
        $strMock = Mockery::mock("alias:" . Str::class);
        $strMock->shouldReceive('random')
                    ->with(64)
                    ->andReturn($token);

        $userData = [
            'username' => 'USER',
            'email' => 'user@gmail.com',
            'password' => 'Senha123'
        ];

        $userMock = Mockery::mock(User::class);

        $userMock->shouldReceive('save')
                    ->once()
                    ->andReturnSelf();

        $this->makeGetAttributeUser('email', $userMock, $this->user->email, $this->user->email, $userData['email']);
        $this->makeGetAttributeUser('password', $userMock, $this->user->password);
        $this->makeGetAttributeUser('token', $userMock, $this->user->token);
        $this->makeGetAttributeUser('username', $userMock, $this->user->username);
        

        $this->makeSetAttributeUser('username', $userMock, $userData['username'],);
        $this->makeSetAttributeUser('token', $userMock, $token);
        $this->makeSetAttributeUser('email', $userMock, $userData['email']);
        $this->makeSetAttributeUser('email_verified_at', $userMock,  null);

        $this->userRepositoryStub->method('emailExists')
                                    ->with($userData['email'])
                                    ->willReturn(false);

        $this->userRepositoryStub->method('getByEmail')
                                    ->with($this->user->email)
                                    ->willReturn($userMock);

        $this->emailServiceMock->shouldReceive('setMailStructure')
                                ->withAnyArgs()
                                ->andReturnSelf();
        
        $this->emailServiceMock->shouldReceive('sendWithPathParams')
                                ->withAnyArgs()
                                ->andReturnSelf();
                    
        $response = $this->userService->updateUserData($userData); 

        $this->assertTrue($response->getStatus());
        $this->assertSame("Email de confirmação enviado! Verifique sua caixa de mensagens.", $response->getMessage());
        $this->assertTrue($response->getData());
    }

    public function test_update_user_data_with_invalid_email(): void
    {
        $userData = [
            'username' => 'USER',
            'email' => 'user@gmail.com',
            'password' => 'Senha123'
        ];

        $userMock = Mockery::mock(User::class);

        $this->makeGetAttributeUser('email', $userMock, $this->user->email);
        
        $this->userRepositoryStub->method('emailExists')
                                    ->with($userData['email'])
                                    ->willReturn(true);

        $this->userRepositoryStub->method('getByEmail')
                                    ->with($this->user->email)
                                    ->willReturn($userMock);
                    
        $response = $this->userService->updateUserData($userData); 

        $this->assertFalse($response->getStatus());
        $this->assertSame("Email inválido", $response->getMessage());
    }

    public function test_update_user_data_with_invalid_password(): void
    {
        $userData = [
            'username' => 'USER',
            'email' => 'user1@gmail.com',
            'password' => 'Senha12'
        ];

        $userMock = Mockery::mock(User::class);

        $this->makeGetAttributeUser('email', $userMock, $this->user->email);
        $this->makeGetAttributeUser('password', $userMock, $this->user->password);

        $this->userRepositoryStub->method('emailExists')
                                    ->with($userData['email'])
                                    ->willReturn(true);

        $this->userRepositoryStub->method('getByEmail')
                                    ->with($this->user->email)
                                    ->willReturn($userMock);
                    
        $response = $this->userService->updateUserData($userData); 

        $this->assertFalse($response->getStatus());
        $this->assertSame("Senha incorreta!", $response->getMessage());
    }

    public function test_update_user_data_with_error_to_send_email(): void
    {

        $token = 'abcdefg';
        $strMock = Mockery::mock("alias:" . Str::class);
        $strMock->shouldReceive('random')
                    ->with(64)
                    ->andReturn($token);

        $userData = [
            'username' => 'USER',
            'email' => 'user@gmail.com',
            'password' => 'Senha123'
        ];

        $newEmail = $userData['email'];
        $oldEmail = $this->user->email;

        $userMock = Mockery::mock(User::class);
        $userMock->shouldReceive('save')
                    ->andReturnSelf();

        $this->makeGetAttributeUser('email', $userMock, $oldEmail, $oldEmail, $newEmail);
        $this->makeGetAttributeUser('password', $userMock, $this->user->password);
        $this->makeGetAttributeUser('token', $userMock, $token);
        $this->makeGetAttributeUser('username', $userMock, $this->user->username);

        $this->makeSetAttributeUser('username', $userMock, $userData['username'],);
        $this->makeSetAttributeUser('token', $userMock, $token);
        $this->makeSetAttributeUser('email', $userMock, $newEmail);
        $this->makeSetAttributeUser('email_verified_at', $userMock,  null);

        $this->userRepositoryStub->method('emailExists')
                                    ->with($newEmail)
                                    ->willReturn(false);

        $this->userRepositoryStub->method('getByEmail')
                                    ->with($oldEmail)
                                    ->willReturn($userMock);

        $this->emailServiceMock->shouldReceive('setMailStructure')
                                ->with($newEmail, Mockery::type(VerifyEmail::class))
                                ->andReturnSelf();
        
        $this->emailServiceMock->shouldReceive('sendWithPathParams')
                                ->with('/verify_email', ['token' => $token])
                                ->andThrow(Exception::class, 'Falha ao enviar email');

        $response = $this->userService->updateUserData($userData); 

        $this->assertFalse($response->getStatus());
        $this->assertSame('Falha ao enviar email', $response->getMessage());
    }

    public function test_register_new_user_successfully(): void
    {

        $userMock = Mockery::mock(User::class);
        $userMock->shouldReceive('save')
                    ->andReturnSelf();

        $userFactoryMock = Mockery::mock('alias:' . UserFactory::class);
        $userFactoryMock->shouldReceive('create')
                            ->andReturn($userMock);

        $userData = [
            'username' => 'USER',
            'email' => 'user@example.com'
        ];
        $this->makeGetAttributeUser('username', $userMock, $userData['username']);
        $this->makeGetAttributeUser('email', $userMock, $userData['email'], $userData['email']);

        $token = 'abcdefg';
        $this->makeGetAttributeUser('token', $userMock, $token);


        $this->emailServiceMock->shouldReceive('setMailStructure')
                                    ->withAnyArgs()
                                    ->andReturnSelf();
        
        $this->emailServiceMock->shouldReceive('sendWithPathParams')
                            ->withAnyArgs()
                            ->andReturnSelf();

        $response = $this->userService->register($userData);
        
        $this->assertTrue($response->getStatus());
        $this->assertSame("Email de confirmação enviado para {$userData['email']}! Verifique a caixa de mensagens.", $response->getMessage());
    }

    public function test_register_new_user_with_error_to_send_email(): void
    {

        $userMock = Mockery::mock(User::class);
        $userMock->shouldReceive('save')
                    ->andReturnSelf();

        $userFactoryMock = Mockery::mock('alias:' . UserFactory::class);
        $userFactoryMock->shouldReceive('create')
                            ->andReturn($userMock);

        $userData = [
            'username' => 'USER',
            'email' => 'user@example.com'
        ];
        $this->makeGetAttributeUser('username', $userMock, $userData['username']);
        $this->makeGetAttributeUser('email', $userMock, $userData['email'], $userData['email']);

        $token = 'abcdefg';
        $this->makeGetAttributeUser('token', $userMock, $token);

        $this->emailServiceMock->shouldReceive('setMailStructure')
                                    ->withAnyArgs()
                                    ->andReturnSelf();
        
        $this->emailServiceMock->shouldReceive('sendWithPathParams')
                            ->withAnyArgs()
                            ->andThrow(Exception::class, "Falha ao enviar email");

        $response = $this->userService->register($userData);
        
        $this->assertFalse($response->getStatus());
        $this->assertSame("Falha ao enviar email", $response->getMessage());
    }

    public function test_delete_user_successfully(): void
    {
        Crypt::expects('decrypt')->andReturn(4);
        
        $userMock = Mockery::mock(User::class);
        
        $this->makeGetAttributeUser('username', $userMock, $this->user->username);
        
        $userMock->shouldReceive('delete')
            ->andReturnSelf();

        $this->userRepositoryStub->method('getOnlyActiveUsersByUsrId')
                                    ->willReturn($userMock);

        $response = $this->userService->delete('EncryptedId');

        $this->assertTrue($response->getStatus());
        $this->assertSame("Usuário {$this->user->username} deletado", $response->getMessage());
    }

    public function test_delete_with_invalid_usr_id(): void
    {
        Crypt::expects('decrypt')->andReturn(4);
        
        $userMock = Mockery::mock(User::class);

        $this->userRepositoryStub->method('getOnlyActiveUsersByUsrId')
                                    ->willReturn(null);

        $response = $this->userService->delete('EncryptedId');

        $this->assertFalse($response->getStatus());
        $this->assertSame("Erro ao deletar usuário", $response->getMessage());
    }

    public function test_update_user_by_admin_view_with_same_email_successfully(): void
    {
        $userData = [
            'username' => 'USER',
            'email' => 'user1@gmail.com',
            'usr_id' => '4',
            'reset_time_balance' => null
        ];

        $userMock = Mockery::mock(User::class);

        $userMock->shouldReceive('save')
                    ->once()
                    ->andReturnSelf();

        $this->makeGetAttributeUser('email', $userMock, $this->user->email);

        $this->makeSetAttributeUser('username', $userMock, $userData['username']);
        
        $this->userRepositoryStub->method('emailExists')
                                    ->with($userData['email'])
                                    ->willReturn(true);

        $this->userRepositoryStub->method('getOnlyActiveUsersByUsrId')
                                    ->with($userData['usr_id'])
                                    ->willReturn($userMock);
                    
        $response = $this->userService->updateByAdminView($userData); 

        $this->assertTrue($response->getStatus());
        $this->assertSame("Dados atualizados com sucesso!", $response->getMessage());
    }

    public function test_update_user_by_admin_view_with_different_email_successfully(): void
    {

        $token = 'abcdefg';
        $strMock = Mockery::mock("alias:" . Str::class);
        $strMock->shouldReceive('random')
                    ->with(64)
                    ->andReturn($token);

        $userData = [
            'username' => 'USER',
            'email' => 'user@gmail.com',
            'usr_id' => '4',
            'reset_time_balance' => null
        ];

        $userMock = Mockery::mock(User::class);

        $this->userRepositoryStub->method('emailExists')
                                    ->with($userData['email'])
                                    ->willReturn(false);

        $this->userRepositoryStub->method('getOnlyActiveUsersByUsrId')
                                    ->with($userData['usr_id'])
                                    ->willReturn($userMock);

        $this->makeGetAttributeUser('email', $userMock, $this->user->email, $this->user->email);
        $this->makeGetAttributeUser('token', $userMock, $token);
        $this->makeGetAttributeUser('username', $userMock, $userData['username']);

        $this->makeSetAttributeUser('token', $userMock, $token);
        $this->makeSetAttributeUser('email', $userMock, $userData['email']);
        $this->makeSetAttributeUser('email_verified_at', $userMock,  null);
        $this->makeSetAttributeUser('username', $userMock, $userData['username']);

        $userMock->shouldReceive('save')
                    ->once()
                    ->andReturnSelf();

        $this->emailServiceMock->shouldReceive('setMailStructure')
                                ->withAnyArgs()
                                ->andReturnSelf();
        
        $this->emailServiceMock->shouldReceive('sendWithPathParams')
                                ->withAnyArgs()
                                ->andReturnSelf();
                    
        $response = $this->userService->updateByAdminView($userData); 

        $this->assertTrue($response->getStatus());
        $this->assertSame("Dados atualizados com sucesso!", $response->getMessage());
    }

    public function test_update_user_by_admin_view_with_different_email_and_reset_time_balance_successfully(): void
    {

        $token = 'abcdefg';
        $strMock = Mockery::mock("alias:" . Str::class);
        $strMock->shouldReceive('random')
                    ->with(64)
                    ->andReturn($token);

        $userData = [
            'username' => 'USER',
            'email' => 'user@gmail.com',
            'usr_id' => 4,
            'reset_time_balance' => true
        ];

        $userMock = Mockery::mock(User::class);

        $this->userRepositoryStub->method('emailExists')
                                    ->with($userData['email'])
                                    ->willReturn(false);

        $this->userRepositoryStub->method('getOnlyActiveUsersByUsrId')
                                    ->with($userData['usr_id'])
                                    ->willReturn($userMock);

        $this->makeGetAttributeUser('email', $userMock, $this->user->email, $this->user->email);
        $this->makeGetAttributeUser('token', $userMock, $token);
        $this->makeGetAttributeUser('username', $userMock, $userData['username']);
        $this->makeGetAttributeUser('usr_id', $userMock, $userData['usr_id']);

        $this->makeSetAttributeUser('token', $userMock, $token);
        $this->makeSetAttributeUser('email', $userMock, $userData['email']);
        $this->makeSetAttributeUser('email_verified_at', $userMock,  null);
        $this->makeSetAttributeUser('username', $userMock, $userData['username']);

        $userMock->shouldReceive('save')
                    ->once()
                    ->andReturnSelf();

        $this->emailServiceMock->shouldReceive('setMailStructure')
                                ->withAnyArgs()
                                ->andReturnSelf();
        
        $this->emailServiceMock->shouldReceive('sendWithPathParams')
                                ->withAnyArgs()
                                ->andReturnSelf();

        
        $this->timeSheetRepositoryMock->method('resetTimeBalanceByUsrId')
                                ->willReturnSelf();
                    
        $response = $this->userService->updateByAdminView($userData); 

        $this->assertTrue($response->getStatus());
        $this->assertSame("Dados atualizados com sucesso!", $response->getMessage());
    }

    public function test_update_user_by_admin_view_with_error_invalid_email(): void
    {

        $token = 'abcdefg';
        $strMock = Mockery::mock("alias:" . Str::class);
        $strMock->shouldReceive('random')
                    ->with(64)
                    ->andReturn($token);

        $userData = [
            'username' => 'USER',
            'email' => 'user@gmail.com',
            'usr_id' => 4,
            'reset_time_balance' => true
        ];

        $userMock = Mockery::mock(User::class);
        
        $this->makeGetAttributeUser('email', $userMock, $this->user->email);

        $this->userRepositoryStub->method('emailExists')
                                    ->with($userData['email'])
                                    ->willReturn(true);

        $this->userRepositoryStub->method('getOnlyActiveUsersByUsrId')
                                    ->with($userData['usr_id'])
                                    ->willReturn($userMock);

        $response = $this->userService->updateByAdminView($userData); 

        $this->assertFalse($response->getStatus());
        $this->assertSame("Email inválido", $response->getMessage());
    }

    public function test_update_user_by_admin_view_with_error_to_send_email(): void
    {

         $token = 'abcdefg';
        $strMock = Mockery::mock("alias:" . Str::class);
        $strMock->shouldReceive('random')
                    ->with(64)
                    ->andReturn($token);

        $userData = [
            'username' => 'USER',
            'email' => 'user@gmail.com',
            'usr_id' => '4',
            'reset_time_balance' => null
        ];

        $userMock = Mockery::mock(User::class);

        $this->userRepositoryStub->method('emailExists')
                                    ->with($userData['email'])
                                    ->willReturn(false);

        $this->userRepositoryStub->method('getOnlyActiveUsersByUsrId')
                                    ->with($userData['usr_id'])
                                    ->willReturn($userMock);

        $this->makeGetAttributeUser('email', $userMock, $this->user->email, $this->user->email);
        $this->makeGetAttributeUser('token', $userMock, $token);
        $this->makeGetAttributeUser('username', $userMock, $userData['username']);

        $this->makeSetAttributeUser('token', $userMock, $token);
        $this->makeSetAttributeUser('email', $userMock, $userData['email']);
        $this->makeSetAttributeUser('email_verified_at', $userMock,  null);
        $this->makeSetAttributeUser('username', $userMock, $userData['username']);

        $userMock->shouldReceive('save')
                    ->once()
                    ->andReturnSelf();

        $this->emailServiceMock->shouldReceive('setMailStructure')
                                ->withAnyArgs()
                                ->andReturnSelf();
        
        $this->emailServiceMock->shouldReceive('sendWithPathParams')
                                ->withAnyArgs()
                                ->andThrow(Exception::class, 'Falha ao enviar email');
                    
        $response = $this->userService->updateByAdminView($userData); 

        $this->assertFalse($response->getStatus());
        $this->assertSame('Falha ao enviar email', $response->getMessage());
    }


    private function makeGetAttributeUser(string $attribute, User&MockInterface $modelMock, ...$values): void
    {
        $modelMock->shouldReceive('getAttribute')
                    ->with($attribute)
                    ->andReturnValues($values);
    }

    private function makeSetAttributeUser(string $attribute, User&MockInterface $modelMock, $value): void
    {
        $modelMock->shouldReceive('setAttribute')
                    ->with($attribute, $value)
                    ->andReturnSelf();
    }
}