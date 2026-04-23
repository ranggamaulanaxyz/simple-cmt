@props(['href', 'icon', 'active' => false])

<a href="{{ $href }}" class="flex items-center uppercase tracking-[0.12em] text-[10px] font-bold py-3 rounded-md sidebar-link transition-all duration-300
          {{ $active
    ? 'text-primary bg-primary/5'
    : 'text-on-surface/50 hover:text-on-surface hover:bg-surface-container' }}" :class="sidebarOpen || window.innerWidth < 1024 ? 'px-4 gap-3' : 'px-0 justify-center'" @if($active) style="border-left: 3px solid #7C3AED;" @endif>
    <span class="material-symbols-outlined text-lg shrink-0" @if($active) style="font-variation-settings: 'FILL' 1;" @endif>{{ $icon }}</span>
    <span x-show="sidebarOpen || window.innerWidth < 1024" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" class="whitespace-nowrap truncate">{{ $slot }}</span>
</a>