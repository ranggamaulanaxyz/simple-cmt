<div class="flex items-center justify-between p-2 hover:bg-surface-container-low rounded border-b border-outline-variant/10 last:border-0 transition-all duration-300 group" id="point-container-{{ $point->id }}" data-id="{{ $point->id }}">
    <div class="flex-1 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary/30 text-base drag-handle cursor-move hover:text-primary transition-colors opacity-0 group-hover:opacity-100">drag_indicator</span>
        <span class="text-sm text-on-surface/70" x-show="editingPoint !== {{ $point->id }}" id="point-name-{{ $point->id }}">{{ $point->name }}</span>
        <form x-show="editingPoint === {{ $point->id }}" method="POST" action="{{ route('configuration.points.update', $point) }}" class="flex-1 flex gap-2" @click.away="editingPoint = null" @submit.prevent="ajaxUpdate($event, 'point')">
            @csrf @method('PUT')
            <input type="hidden" name="type_signal_id" value="{{ $signal->id }}">
            <input name="name" type="text" value="{{ $point->name }}" class="flex-1 bg-white rounded-md py-1 px-3 text-xs border border-primary/20 focus:ring-1 focus:ring-primary/20">
            <button type="submit" class="flex items-center p-1 bg-primary text-white rounded transition-colors disabled:opacity-50" :disabled="saving">
                <span class="material-symbols-outlined text-sm" x-show="!saving">check</span>
                <span class="material-symbols-outlined text-sm animate-spin" x-show="saving">sync</span>
            </button>
        </form>
    </div>
    <div class="flex items-center gap-1 opacity-10 group-hover:opacity-100 transition-opacity duration-300" x-show="editingPoint !== {{ $point->id }}">
        <button @click="editingPoint = {{ $point->id }}" class="w-8 h-8 flex items-center justify-center hover:bg-primary/10 text-primary/60 hover:text-primary rounded-full transition-all">
            <span class="material-symbols-outlined text-sm">edit</span>
        </button>
        <form method="POST" action="{{ route('configuration.points.destroy', $point) }}" class="inline" @submit.prevent="ajaxDelete($event, 'point-container-{{ $point->id }}')">
            @csrf @method('DELETE')
            <button type="submit" class="w-8 h-8 flex items-center justify-center hover:bg-error-bg text-error/60 hover:text-error rounded-full transition-all">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </form>
    </div>
</div>
