<?php

namespace App\Utils;

class Functions {

    public static function passwordHash($password): string
    {
        return bcrypt($password);
    }
}