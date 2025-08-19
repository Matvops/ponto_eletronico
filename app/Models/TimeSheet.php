<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeSheet extends Model
{
    public $table = 'time_sheet';
    public $primaryKey = 'tis_id';

    public $fillable = [
        'tie_usr_id',
        'date',
        'type',
        'created_at',
        'updated_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tie_usr_id');
    }
}
