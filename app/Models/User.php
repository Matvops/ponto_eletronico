<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as AuthUser;

class User extends AuthUser
{

    use SoftDeletes;
    
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

    public function timeSheets(): HasMany
    {
        return $this->hasMany(TimeSheet::class, 'tie_usr_id');
    }
}
