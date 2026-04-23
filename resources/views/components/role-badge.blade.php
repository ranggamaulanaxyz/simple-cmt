@props(['role'])

@php
    $config = match ($role) {
        'admin' => ['label' => 'Admin', 'bg' => 'bg-primary/5', 'text' => 'text-primary', 'border' => 'border-primary/20'],
        'pimpinan' => ['label' => 'Pimpinan', 'bg' => 'bg-secondary/5', 'text' => 'text-secondary', 'border' => 'border-secondary/20'],
        'pegawai' => ['label' => 'Pegawai', 'bg' => 'bg-surface-container-high', 'text' => 'text-on-surface-variant', 'border' => 'border-outline-variant/30'],
        default => ['label' => $role, 'bg' => 'bg-surface-container', 'text' => 'text-on-surface-variant', 'border' => 'border-outline-variant/30'],
    };
@endphp

<span class="px-2.5 py-1 {{ $config['bg'] }} {{ $config['text'] }} text-[10px] font-bold rounded-sm border {{ $config['border'] }} uppercase tracking-widest">
    {{ $config['label'] }}
</span>