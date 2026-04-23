<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RcPoint extends Model
{
    protected $fillable = ['type_signal_id', 'name', 'sequence'];

    public function typeSignal(): BelongsTo
    {
        return $this->belongsTo(RcTypeSignal::class, 'type_signal_id');
    }
}
