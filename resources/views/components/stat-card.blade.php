@props(['label', 'value', 'change' => null, 'changeType' => 'up', 'borderColor' => 'primary', 'icon' => null])

<div class="bg-white p-6 rounded-xl card-hover relative overflow-hidden"
     style="border-left: 4px solid var(--color-{{ $borderColor }}); box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
    <div class="relative z-10">
        <div class="flex justify-between items-start mb-3">
            <span class="text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface/40">{{ $label }}</span>
            @if($change)
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-sm
                {{ $changeType === 'up' ? 'text-success bg-success-bg' : 'text-error bg-error-bg' }}">
                {{ $changeType === 'up' ? '+' : '' }}{{ $change }}
            </span>
            @endif
        </div>
        <h3 class="text-2xl font-extrabold text-on-surface">{{ $value }}</h3>
        @if(isset($sub))
        <p class="text-xs text-on-surface/50 mt-1">{{ $sub }}</p>
        @endif
    </div>
    @if($icon)
    <div class="absolute -right-3 -bottom-3 opacity-[0.04]">
        <span class="material-symbols-outlined text-[80px] text-primary" style="font-variation-settings: 'FILL' 1;">{{ $icon }}</span>
    </div>
    @endif
</div>
