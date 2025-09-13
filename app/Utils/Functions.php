<?php

namespace App\Utils;

class Functions {

    public static function passwordHash($password): string
    {
        return bcrypt($password);
    }

    public static function regenerateSession(): void
    {
        session()->regenerate();
    }
}