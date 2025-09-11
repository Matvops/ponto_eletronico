<?php

namespace Tests\Unit\Email;

use App\Mail\VerifyEmail;
use App\Services\EmailService;
use Exception;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\PendingMail;
use Illuminate\Support\Facades\Mail;
use Mockery;
use PHPUnit\Framework\Attributes\RunClassInSeparateProcess;
use PHPUnit\Framework\TestCase;

#[RunClassInSeparateProcess]
final class EmailServiceTest extends TestCase {

    private EmailService $emailService;
    private Mailable $mailable;
    private $email;

    protected function setUp(): void
    {
        $this->email = 'user@example.com';
        $this->mailable = Mockery::mock(VerifyEmail::class);
        $this->emailService = new EmailService();
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_send_email_with_error_invalid_email(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Falha ao enviar email');
        $this->emailService->send();
    }

    public function test_send_email_with_error_to_send_email(): void
    {

        $pendingMailMock = Mockery::mock(PendingMail::class);

        $pendingMailMock->shouldReceive('send')
                            ->with($this->mailable)
                            ->andReturnNull();
        Mail::expects('to')
                ->with($this->email)
                ->andReturn($pendingMailMock);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Falha ao enviar email');

        $this->emailService->setMailStructure($this->email, $this->mailable);
        $this->emailService->send();
    }


     public function test_send_with_path_params_email_with_error_invalid_email(): void
    {
        
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Falha ao enviar email');

        $path = '/verifyEmail';
        $params = ['param1' => 'valuee'];

        $this->emailService->sendWithPathParams($path, $params);
    }

    public function test_send_with_path_params_email_with_error_to_send_email(): void
    {
        
        $pendingMailMock = Mockery::mock(PendingMail::class);

        $pendingMailMock->shouldReceive('send')
                            ->with($this->mailable)
                            ->andReturnNull();
        Mail::expects('to')
                ->with($this->email)
                ->andReturn($pendingMailMock);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Falha ao enviar email');

        $path = '/verifyEmail';
        $params = ['param1' => 'valuee'];

        $this->mailable->shouldReceive('setLink')
                        ->withAnyArgs()
                        ->andReturnSelf();

        $this->emailService->setMailStructure($this->email, $this->mailable);
        $this->emailService->sendWithPathParams($path, $params);
    }
}