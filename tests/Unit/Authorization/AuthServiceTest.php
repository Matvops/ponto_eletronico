<?php

namespace Tests\Unit\Authorization;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\AuthService;
use App\Services\EmailService;
use App\Utils\Functions;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\RunClassInSeparateProcess;
use PHPUnit\Framework\MockObject\Stub;
use PHPUnit\Framework\TestCase;

#[RunClassInSeparateProcess]
class AuthServiceTest extends TestCase {

    private AuthService $authService;
    private EmailService&MockInterface $emailServiceMock;
    private UserRepository&Stub $userRepositoryStub;

    protected function setUp(): void
    {
        $this->emailServiceMock = Mockery::mock(EmailService::class);
        $this->userRepositoryStub = $this->createStub(UserRepository::class);
        $this->authService = new AuthService($this->emailServiceMock, $this->userRepositoryStub);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_authentication_successfully(): void
    {
        Auth::expects('login')->andReturnSelf();

        $functionsMock = Mockery::mock('alias:' . Functions::class);

        $functionsMock->shouldReceive('regenerateSession')
                        ->andReturnSelf();

        $userMock = Mockery::mock(User::class);

        $this->makeGetAttributeUser('email_verified_at', $userMock, '2025-08-11 22:04:10.000');
        $this->makeGetAttributeUser('password', $userMock, '$2y$12$tYiw4TP.bVjUiHS4vFE2OOD.6.cp4jua1jxJHn9Am1XnoFZ1ocrEy');

        $this->userRepositoryStub->method('getOnlyActiveUsersByEmail')
                                    ->willReturn($userMock);

        $dados = [
            'email' => 'user@example.com',
            'password' => 'Senha123'
        ];

        $response = $this->authService->authentication($dados);
        
        $this->assertTrue($response->getStatus());
    }

    public function test_authentication_with_error_invalid_email(): void
    {
        $this->userRepositoryStub->method('getOnlyActiveUsersByEmail')
                                    ->willReturn(null);

        $dados = [
            'email' => 'user@example.com',
            'password' => 'Senha123'
        ];

        $response = $this->authService->authentication($dados);
        
        $this->assertFalse($response->getStatus());
        $this->assertSame("Email ou senha são inválidos", $response->getMessage());
    }

    public function test_authentication_with_error_email_not_verified(): void
    {
        $userMock = Mockery::mock(User::class);

        $this->makeGetAttributeUser('email_verified_at', $userMock, null);
        $this->makeGetAttributeUser('password', $userMock, '$2y$12$tYiw4TP.bVjUiHS4vFE2OOD.6.cp4jua1jxJHn9Am1XnoFZ1ocrEy');

        $this->userRepositoryStub->method('getOnlyActiveUsersByEmail')
                                    ->willReturn($userMock);

        $dados = [
            'email' => 'user@example.com',
            'password' => 'Senha123'
        ];

        $response = $this->authService->authentication($dados);
        
        $this->assertFalse($response->getStatus());
        $this->assertSame("Verifique seu email.", $response->getMessage());
    }

    public function test_authentication_with_error_invalid_password(): void
    {

        $userMock = Mockery::mock(User::class);

        $this->makeGetAttributeUser('email_verified_at', $userMock, '2025-08-11 22:04:10.000');
        $this->makeGetAttributeUser('password', $userMock, '$2y$12$tYiw4TP.bVjUiHS4vFE2OOD.6.cp4jua1jxJHn9Am1XnoFZ1ocrEy');

        $this->userRepositoryStub->method('getOnlyActiveUsersByEmail')
                                    ->willReturn($userMock);

        $dados = [
            'email' => 'user@example.com',
            'password' => 'SenhaErrada'
        ];

        $response = $this->authService->authentication($dados);
        
        $this->assertFalse($response->getStatus());
        $this->assertSame("Email ou senha são inválidos", $response->getMessage());
    }
    
    public function test_send_email_confirmation_successfully(): void
    {

        DB::expects('beginTransaction')
                ->andReturnSelf();

        DB::expects('commit')
            ->andReturnSelf();

        $maxDigits = 4;
        $randomCode = 1234;
        $functionsMock = Mockery::mock('alias:' . Functions::class);
        $functionsMock->shouldReceive('generateRandomCode')
                        ->with($maxDigits)
                        ->andReturn($randomCode);

        $userMock = Mockery::mock(User::class);
        
        $userMock->shouldReceive('save')->andReturnSelf();
                        
        $email = 'user@example.com';
        $this->makeGetAttributeUser('email_verified_at', $userMock, '2025-08-11 22:04:10.000');
        $this->makeGetAttributeUser('email', $userMock, $email, $email);
        $this->makeGetAttributeUser('confirmation_code', $userMock, $randomCode);
        $this->makeGetAttributeUser('username', $userMock, 'user');

        $this->makeSetAttributeUser('confirmation_code', $userMock, $randomCode);

        $this->userRepositoryStub->method('getOnlyActiveUsersByEmail')
                                    ->willReturn($userMock);

        $this->emailServiceMock->shouldReceive('setMailStructure')
                                    ->withAnyArgs()
                                    ->andReturnSelf();

        $this->emailServiceMock->shouldReceive('send')
                                ->withAnyArgs()
                                ->andReturnSelf();

        $response = $this->authService->sendEmailConfirmation($email);

        $this->assertTrue($response->getStatus());
        $this->assertSame($email, $response->getData());
    }

    public function test_send_email_confirmation_with_error_invalid_email(): void
    {

        DB::expects('beginTransaction')
                ->andReturnSelf();

        DB::expects('rollback')
            ->andReturnSelf();


        $this->userRepositoryStub->method('getOnlyActiveUsersByEmail')
                                    ->willReturn(null);

        $email = 'user@example.com';
        $response = $this->authService->sendEmailConfirmation($email);

        $this->assertFalse($response->getStatus());
        $this->assertSame("Erro ao enviar email", $response->getMessage());
    }

    public function test_send_email_confirmation_with_error_email_not_verified(): void
    {

        DB::expects('beginTransaction')
                ->andReturnSelf();

        DB::expects('rollback')
            ->andReturnSelf();

        $userMock = Mockery::mock(User::class);
        
        $userMock->shouldReceive('save')->andReturnSelf();
                        
        $email = 'user@example.com';
        $this->makeGetAttributeUser('email_verified_at', $userMock, null);
        $this->makeGetAttributeUser('email', $userMock, $email);

        $this->userRepositoryStub->method('getOnlyActiveUsersByEmail')
                                    ->willReturn($userMock);

        $response = $this->authService->sendEmailConfirmation($email);

        $this->assertFalse($response->getStatus());
        $this->assertSame("Verifique seu cadastro no email", $response->getMessage());
    }

    public function test_send_email_confirmation_with_error_send_email(): void
    {
        DB::expects('beginTransaction')
                ->andReturnSelf();

        DB::expects('rollback')
            ->andReturnSelf();

        $maxDigits = 4;
        $randomCode = 1234;
        $functionsMock = Mockery::mock('alias:' . Functions::class);
        $functionsMock->shouldReceive('generateRandomCode')
                        ->with($maxDigits)
                        ->andReturn($randomCode);

        $userMock = Mockery::mock(User::class);
        
        $userMock->shouldReceive('save')->andReturnSelf();
                        
        $email = 'user@example.com';
        $this->makeGetAttributeUser('email_verified_at', $userMock, '2025-08-11 22:04:10.000');
        $this->makeGetAttributeUser('email', $userMock, $email, $email);
        $this->makeGetAttributeUser('confirmation_code', $userMock, $randomCode);
        $this->makeGetAttributeUser('username', $userMock, 'user');

        $this->makeSetAttributeUser('confirmation_code', $userMock, $randomCode);

        $this->userRepositoryStub->method('getOnlyActiveUsersByEmail')
                                    ->willReturn($userMock);

        $this->emailServiceMock->shouldReceive('setMailStructure')
                                    ->withAnyArgs()
                                    ->andReturnSelf();

        $this->emailServiceMock->shouldReceive('send')
                                ->withAnyArgs()
                                ->andThrow(Exception::class, "Falha ao enviar email");

        $response = $this->authService->sendEmailConfirmation($email);

        $this->assertFalse($response->getStatus());
        $this->assertSame("Falha ao enviar email", $response->getMessage());
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