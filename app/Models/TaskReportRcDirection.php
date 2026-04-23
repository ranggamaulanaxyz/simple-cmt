<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskReportRcDirection extends Model
{
    protected $fillable = ['report_id', 'arah_remote_control', 'penyulang'];

    public function commissionings(): HasMany
    {
        return $this->hasMany(TaskReportRcCommissioning::class, 'rc_direction_id');
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(TaskReport::class, 'report_id');
    }
}
