@props(['status'])

@php
    $user = auth()->user();
    $isPimpinan = $user && $user->isPimpinan();

    $config = match ($status) {
        'draft' => ['label' => 'Draft', 'bg' => 'bg-surface-container-high', 'text' => 'text-on-surface-variant', 'border' => 'border-outline-variant/30'],
        'disubmit' => ['label' => 'Disubmit', 'bg' => 'bg-info-bg', 'text' => 'text-info', 'border' => 'border-info/20'],
        'terverifikasi' => ['label' => $isPimpinan ? 'Menunggu' : 'Terverifikasi', 'bg' => 'bg-success-bg', 'text' => 'text-success', 'border' => 'border-success/20'],
        'ditolak' => ['label' => 'Ditolak', 'bg' => 'bg-error-bg', 'text' => 'text-error', 'border' => 'border-error/20'],
        'direview' => ['label' => $isPimpinan ? 'Direview' : 'Selesai', 'bg' => 'bg-primary/5', 'text' => 'text-primary', 'border' => 'border-primary/20'],
        // Detailed statuses
        'Menunggu' => ['label' => 'Menunggu', 'bg' => 'bg-warning-bg', 'text' => 'text-warning', 'border' => 'border-warning/20'],
        'Disetujui' => ['label' => 'Disetujui', 'bg' => 'bg-success-bg', 'text' => 'text-success', 'border' => 'border-success/20'],
        'Ditolak (Admin)' => ['label' => 'Ditolak', 'bg' => 'bg-error-bg', 'text' => 'text-error', 'border' => 'border-error/20'],
        'Ditolak (Pimpinan)' => ['label' => 'Ditolak', 'bg' => 'bg-error-bg', 'text' => 'text-error', 'border' => 'border-error/20'],
        default => ['label' => $status, 'bg' => 'bg-surface-container', 'text' => 'text-on-surface-variant', 'border' => 'border-outline-variant/30'],
    };
@endphp

<span class="px-2.5 py-1 {{ $config['bg'] }} {{ $config['text'] }} text-[10px] font-bold rounded-sm border {{ $config['border'] }} uppercase tracking-widest">
    {{ $config['label'] }}
</span>