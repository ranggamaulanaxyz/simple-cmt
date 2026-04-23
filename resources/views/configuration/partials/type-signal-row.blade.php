<div class="mb-6" id="type-container-{{ $signal->id }}" data-id="{{ $signal->id }}">
    <div class="flex items-center justify-between p-3 bg-white rounded-lg mb-2 group transition-all duration-300 hover:shadow-md border border-outline-variant/30">
        <div class="flex-1 flex items-center gap-3">
            <span class="material-symbols-outlined text-primary text-xl drag-handle cursor-move hover:text-primary-dark transition-colors">drag_indicator</span>
            <span class="material-symbols-outlined text-primary text-lg">settings_remote</span>
            <div class="flex-1" x-show="editingType !== {{ $signal->id }}">
                <span class="font-bold text-on-surface text-sm" id="type-name-{{ $signal->id }}">{{ $signal->name }}</span>
                <span class="text-[10px] text-on-surface/40 font-bold bg-surface-container px-2 py-0.5 rounded-sm ml-2">{{ $signal->points->count() }} points</span>
            </div>
            <form x-show="editingType === {{ $signal->id }}" method="POST" action="{{ route('configuration.type-signals.update', $signal) }}" class="flex-1 flex items-center gap-2" @click.away="editingType = null" @submit.prevent="ajaxUpdate($event, 'type')">
                @csrf @method('PUT')
                <input name="name" type="text" value="{{ $signal->name }}" class="flex-1 bg-white rounded-md py-1.5 px-3 text-sm border border-primary/20 focus:ring-1 focus:ring-primary/20">
                <button type="submit" class="w-9 h-9 flex items-center justify-center bg-primary text-white rounded-full transition-colors disabled:opacity-50" :disabled="saving">
                    <span class="material-symbols-outlined text-base" x-show="!saving">check</span>
                    <span class="material-symbols-outlined text-base animate-spin" x-show="saving">sync</span>
                </button>
            </form>
        </div>
        <div class="flex items-center gap-1" x-show="editingType !== {{ $signal->id }}">
            <button @click="editingType = {{ $signal->id }}" class="w-9 h-9 flex items-center justify-center hover:bg-primary/10 text-primary/60 hover:text-primary rounded-full transition-all duration-300">
                <span class="material-symbols-outlined text-base">edit</span>
            </button>
            <form method="POST" action="{{ route('configuration.type-signals.destroy', $signal) }}" class="inline" @submit.prevent="ajaxDelete($event, 'type-container-{{ $signal->id }}')">
                @csrf @method('DELETE')
                <button type="submit" class="w-9 h-9 flex items-center justify-center hover:bg-error-bg text-error/60 hover:text-error rounded-full transition-all duration-300">
                    <span class="material-symbols-outlined text-base">delete</span>
                </button>
            </form>
        </div>
    </div>

    <div class="ml-8 space-y-1" id="points-list-{{ $signal->id }}">
        @foreach($signal->points as $point)
            @include('configuration.partials.point-row', ['point' => $point, 'signal' => $signal])
        @endforeach

        {{-- Add Point --}}
        <form method="POST" action="{{ route('configuration.points.store') }}" class="flex items-center gap-2 mt-2" @submit.prevent="ajaxStore($event, 'points-list-{{ $signal->id }}')">
            @csrf
            <input type="hidden" name="type_signal_id" value="{{ $signal->id }}">
            <input name="name" type="text" required placeholder="Nama point baru..." class="flex-1 bg-surface-container-low rounded-md py-2 px-3 text-xs border-none">
            <button type="submit" class="w-8 h-8 flex items-center justify-center bg-primary/10 hover:bg-primary text-primary hover:text-white rounded-full transition-all disabled:opacity-50" :disabled="saving">
                <span class="material-symbols-outlined text-sm" x-show="!saving">add</span>
                <span class="material-symbols-outlined text-sm animate-spin" x-show="saving">sync</span>
            </button>
        </form>
    </div>
</div>
