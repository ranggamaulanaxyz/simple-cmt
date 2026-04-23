<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RcTypeSignal extends Model
{
    protected $fillable = ['name', 'sequence'];

    public function points(): HasMany
    {
        return $this->hasMany(RcPoint::class, 'type_signal_id');
    }
}
