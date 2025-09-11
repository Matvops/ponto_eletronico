<?php

namespace App\Factories;

use App\Models\User;
use App\Utils\Functions;
use Illuminate\Support\Str;

class UserFactory {

    public static function create(array $dados): User
    {
        $user = new User();
        $user->username = $dados['username'];
        $user->email = $dados['email'];
        $user->password = Functions::passwordHash($dados['password']);
        $user->role = strtoupper($dados['role']);
        $user->token = Str::random(64);

        return $user;
    }
}