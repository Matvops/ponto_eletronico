<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as AuthUser;

class User extends AuthUser
{
    public $table = 'users';
    public $primaryKey = 'usr_id';
    
    public $fillable = [
        'username',
        'email',
        'password',
        'role',
        'email_verified_at',
        'token',
        'created_at',
        'updated_at',
        'deleted_at',
        'confirmation_code',
    ];
}
