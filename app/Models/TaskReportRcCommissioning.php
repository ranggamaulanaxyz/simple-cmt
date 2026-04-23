<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskReportRcCommissioning extends Model
{
    protected $fillable = [
        'report_id', 'rc_direction_id', 'database_field', 'point_id', 'terminal_kubiker',
        'signaling_gh', 'signaling_dcc', 'control_dcc_rtu',
        'control_dcc_rele_plat', 'control_dcc_lbs', 'note',
    ];

    public function direction(): BelongsTo
    {
        return $this->belongsTo(TaskReportRcDirection::class, 'rc_direction_id');
    }

    protected function casts(): array
    {
        return [
            'signaling_gh' => 'boolean',
            'signaling_dcc' => 'boolean',
            'control_dcc_rtu' => 'boolean',
            'control_dcc_rele_plat' => 'boolean',
            'control_dcc_lbs' => 'boolean',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(TaskReport::class, 'report_id');
    }

    public function point(): BelongsTo
    {
        return $this->belongsTo(RcPoint::class, 'point_id');
    }
}
