<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    public $table = 'holidays';
    public $primaryKey = 'hol_id';
    public $timestamps = false;

    public $fillable = [
        'date',
        'description',
    ];
}
