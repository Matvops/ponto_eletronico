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
}