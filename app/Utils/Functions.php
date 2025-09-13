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

    public static function generateRandomCode($maxDigits): int
    {
        $random = rand(1, 9999);

        $code = str_pad(strval($random), $maxDigits, '0', STR_PAD_RIGHT);

        return intval($code);

    }
}