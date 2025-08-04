<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    public $table = 'time_entries';
    public $primaryKey = 'tie_id';

    public $fillable = [
        'tie_ent_id',
        'tie_usr_id',
        'date',
        'created_at',
        'updated_at'
    ];

    public function entryType(): BelongsTo
    {
        return $this->belongsTo(EntryType::class, 'tie_ent_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tie_usr_id');
    }
}
