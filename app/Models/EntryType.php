<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EntryType extends Model
{
    public $table = "entry_types";
    public $primaryKey = "ent_id";
    public $timestamps = false;

    public $fillable = [
        'ent_id',
        'description'
    ];

    public function timeEntries(): HasMany
    {
        return $this->hasMany(TimeEntry::class, 'tie_ent_id');
    }
}
