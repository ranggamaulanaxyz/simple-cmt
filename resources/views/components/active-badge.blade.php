@props(['status'])

@php
    $config = match ($status) {
        'active' => ['label' => 'Aktif', 'bg' => 'bg-success-bg', 'text' => 'text-success', 'border' => 'border-success/20'],
        'inactive' => ['label' => 'Nonaktif', 'bg' => 'bg-error-bg', 'text' => 'text-error', 'border' => 'border-error/20'],
        default => ['label' => $status, 'bg' => 'bg-surface-container', 'text' => 'text-on-surface-variant', 'border' => 'border-outline-variant/30'],
    };
@endphp

<span class="px-2.5 py-1 {{ $config['bg'] }} {{ $config['text'] }} text-[10px] font-bold rounded-sm border {{ $config['border'] }} uppercase tracking-widest">
    {{ $config['label'] }}
</span>