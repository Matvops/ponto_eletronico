<?php

namespace App\Services;

use Exception;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class EmailService {

    private $email;
    private $mailable;

    public function __construct(?string $email = null, ?Mailable $mailable = null)
    {
        $this->email = $email;
        $this->mailable = $mailable;
    }

    public function send(): void
    {

        $response = false;
        if($this->email && $this->mailable) {
            $response =  Mail::to($this->email)->send($this->mailable);
        }

        if(!$response) throw new Exception("Falha ao enviar email");
    }

    public function sendWithPathParams(string $path, array $pathParams): void
    {
     
        $url = $this->buildUrl($path, $pathParams);
        $this->mailable->setLink($url);

        $response = Mail::to($this->email)->send($this->mailable);

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

    public function setMailStructure(string $email, Mailable $mailable) {
        $this->email = $email;
        $this->mailable = $mailable;
    }
}