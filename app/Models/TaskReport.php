<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class TaskReport extends Model
{
    protected $fillable = [
        'type', 'date', 'status', 'status_admin', 'status_pimpinan', 'user_id', 'gardu', 'address',
        'latitude', 'longitude', 'arah', 'notes', 'rejection_note', 'arah_gardu',
        // RC fields
        'rtu', 'modem', 'lokasi', 'date_commissioning', 'denah_sld_file', 'commissioning_notes',
        // GFD fields
        'task_type', 'penyulang', 'gardu_induk', 'type_gardu',
        'old_gfd', 'old_gfd_type_serial_number', 'old_gfd_setting_kepekaan_arus',
        'old_gfd_setting_kepekaan_waktu', 'old_gfd_setting_waktu',
        'old_gfd_injek_arus', 'old_gfd_condition',
        'new_gfd', 'new_gfd_type_serial_number', 'new_gfd_setting_kepekaan_arus',
        'new_gfd_setting_kepekaan_waktu', 'new_gfd_setting_waktu',
        'new_gfd_injek_arus', 'new_gfd_condition',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'date_commissioning' => 'date',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(TaskReportImage::class, 'report_id');
    }

    public function rcDirections(): HasMany
    {
        return $this->hasMany(TaskReportRcDirection::class, 'report_id');
    }

    public function rcCommissionings(): HasMany
    {
        return $this->hasMany(TaskReportRcCommissioning::class, 'report_id');
    }

    public function gfdInspections(): HasMany
    {
        return $this->hasMany(TaskReportGfdInspection::class, 'report_id');
    }

    // Scopes
    public function scopeByStatus(Builder $query, $status): Builder
    {
        return is_array($status) ? $query->whereIn('status', $status) : $query->where('status', $status);
    }

    public function scopeByType(Builder $query, $type): Builder
    {
        return is_array($type) ? $query->whereIn('type', $type) : $query->where('type', $type);
    }

    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    // Helpers
    public function isRc(): bool
    {
        return $this->type === 'rc';
    }

    public function isGfd(): bool
    {
        return $this->type === 'gfd';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'disubmit';
    }

    public function isVerified(): bool
    {
        return $this->status === 'terverifikasi';
    }

    public function isRejected(): bool
    {
        return $this->status === 'ditolak';
    }

    public function isReviewed(): bool
    {
        return $this->status === 'direview';
    }

    public function canBeEdited(): bool
    {
        return in_array($this->status, ['draft', 'ditolak']);
    }

    public function canBeDeleted(): bool
    {
        return $this->status === 'draft';
    }

    public function canBeSubmitted(): bool
    {
        return in_array($this->status, ['draft', 'ditolak']);
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['disubmit', 'ditolak']);
    }

    public function canBeVerified(): bool
    {
        return $this->status === 'disubmit';
    }

    public function canBeRejected(): bool
    {
        return in_array($this->status, ['disubmit', 'terverifikasi']);
    }

    public function canBeReviewed(): bool
    {
        return $this->status === 'terverifikasi';
    }

    public function getStatusLabelAttribute(): string
    {
        $user = auth()->user();
        $isPimpinan = $user && $user->isPimpinan();

        return match ($this->status) {
            'draft' => 'Draft',
            'disubmit' => 'Disubmit',
            'terverifikasi' => $isPimpinan ? 'Menunggu' : 'Terverifikasi',
            'ditolak' => 'Ditolak',
            'direview' => $isPimpinan ? 'Direview' : 'Selesai',
            default => $this->status,
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'rc' => 'Remote Control',
            'gfd' => 'Ground Fault Detector',
            default => $this->type,
        };
    }
}
