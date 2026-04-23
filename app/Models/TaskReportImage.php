<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskReportImage extends Model
{
    protected $fillable = ['report_id', 'description', 'image'];

    public function report(): BelongsTo
    {
        return $this->belongsTo(TaskReport::class, 'report_id');
    }
}
