<?php

namespace App\Services;

use App\Mail\VerifyEmail;
use App\Models\User;
use Exception;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class EmailService {

    private User $user;
    private Mailable $mailable;

    public function __construct(User $user, Mailable $mailable)
    {
        $this->user = $user;
        $this->mailable = $mailable;
    }

    public function send(): void
    {
        $response =  Mail::to($this->user->email)->send($this->mailable);

        if(!$response) throw new Exception("Falha ao enviar email");
    }

    public function sendWithPathParams(string $path, array $pathParams): void
    {
     
        $url = $this->buildUrl($path, $pathParams);
        $this->mailable->setLink($url);

        $response = Mail::to($this->user->email)->send($this->mailable);

        if(!$response) throw new Exception("Falha ao enviar email");
    }

    private function buildUrl(string $path, array $pathParams): string
    {

        $base = env("BASE_URL_LOCAL");
        $url = $path[0] === '/' ? $base . $path . '/' :  $base . '/' . $path . '/';
        

        if(!$pathParams) return $url;

        foreach($pathParams as $pathParam) 
            $url.= $pathParam . '/';
        

        return $url;
    }
}