<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskReportGfdInspection extends Model
{
    protected $fillable = ['report_id', 'item_id', 'ada', 'tidak_ada', 'rusak'];

    protected function casts(): array
    {
        return [
            'ada' => 'boolean',
            'tidak_ada' => 'boolean',
            'rusak' => 'boolean',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(TaskReport::class, 'report_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(GfdInspectionItem::class, 'item_id');
    }
}
