<div class="flex items-center justify-between p-3 hover:bg-surface-container-low rounded-lg border border-outline-variant/10 mb-1 transition-all duration-300 group" id="gfd-container-{{ $item->id }}" data-id="{{ $item->id }}">
    <div class="flex-1 flex items-center gap-3">
        <span class="material-symbols-outlined text-secondary/30 text-xl drag-handle cursor-move hover:text-secondary-dark transition-colors opacity-0 group-hover:opacity-100">drag_indicator</span>
        <span class="material-symbols-outlined text-secondary/60 text-lg">electrical_services</span>
        <span class="text-sm text-on-surface/80" x-show="editingGfd !== {{ $item->id }}" id="gfd-name-{{ $item->id }}">{{ $item->name }}</span>
        <form x-show="editingGfd === {{ $item->id }}" method="POST" action="{{ route('configuration.gfd-items.update', $item) }}" class="flex-1 flex gap-2" @click.away="editingGfd = null" @submit.prevent="ajaxUpdate($event, 'gfd')">
            @csrf @method('PUT')
            <input name="name" type="text" value="{{ $item->name }}" class="flex-1 bg-white rounded-md py-1.5 px-3 text-sm border border-secondary/20 focus:ring-1 focus:ring-secondary/20">
            <button type="submit" class="w-9 h-9 flex items-center justify-center bg-secondary text-white rounded-full transition-colors disabled:opacity-50" :disabled="saving">
                <span class="material-symbols-outlined text-base" x-show="!saving">check</span>
                <span class="material-symbols-outlined text-base animate-spin" x-show="saving">sync</span>
            </button>
        </form>
    </div>
    <div class="flex items-center gap-1 opacity-10 group-hover:opacity-100 transition-opacity duration-300" x-show="editingGfd !== {{ $item->id }}">
        <button @click="editingGfd = {{ $item->id }}" class="w-9 h-9 flex items-center justify-center hover:bg-secondary/10 text-secondary/60 hover:text-secondary rounded-full transition-all">
            <span class="material-symbols-outlined text-sm">edit</span>
        </button>
        <form method="POST" action="{{ route('configuration.gfd-items.destroy', $item) }}" class="inline" @submit.prevent="ajaxDelete($event, 'gfd-container-{{ $item->id }}')">
            @csrf @method('DELETE')
            <button type="submit" class="w-9 h-9 flex items-center justify-center hover:bg-error-bg text-error/60 hover:text-error rounded-full transition-all">
                <span class="material-symbols-outlined text-error text-sm">close</span>
            </button>
        </form>
    </div>
</div>
