<?php

namespace App\Services;

use App\Mail\ConfirmationCode;
use App\Mail\VerifyEmail;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Mail;

class EmailService {

    
    public function sendConfirmationCode(User $user) : void
    {
        $response =  Mail::to($user->email)->send(new ConfirmationCode($user->confirmation_code, $user->username));

        if(!$response) throw new Exception("Falha ao enviar email");
    }

    public static function sendWithPathParams(string $path, array $pathParams, User $user): void
    {
        
        $url = self::buildUrl($path, $pathParams);

        $response = Mail::to($user->email)->send(new VerifyEmail($user->username, $url));

        if(!$response) throw new Exception("Falha ao enviar email");
    }

    private static function buildUrl(string $path, array $pathParams): string
    {

        $base = env("BASE_URL_LOCAL");
        $url = $path[0] === '/' ? $base . $path . '/' :  $base . '/' . $path . '/';
        

        if(!$pathParams) return $url;

        foreach($pathParams as $pathParam) 
            $url.= $pathParam . '/';
        

        return $url;
    }
}