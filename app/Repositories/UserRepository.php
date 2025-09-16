<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository {


    public function emailExists($email)
    {
        return User::withTrashed()
                ->where('email', $email)
                ->exists();
    }

    public function getByEmail($email)
    {
        return User::withTrashed()
                ->where('email', $email)
                ->first();
    }

    public function getOnlyActiveUsersByUsrId($usr_id) 
    {
        return User::find($usr_id);
    }

    public function getOnlyActiveUsersByEmail($email)
    {
        return User::where('email', $email)
                        ->whereNull('deleted_at')
                        ->first();
    }

    public function getOnlyActiveUsersWithEmailNotVerifiedByToken($token)
    {
        return User::where('token', $token)
                            ->whereNull('email_verified_at')
                            ->whereNull('deleted_at')
                            ->first();
    }
}