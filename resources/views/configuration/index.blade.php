@extends('layouts.app')
@section('title', 'Konfigurasi')
@section('page-title', 'Konfigurasi')

@section('content')
    <div class="space-y-8" x-data="{ 
                editingType: null, 
                editingPoint: null, 
                editingGfd: null,
                saving: false,
                async ajaxUpdate(e, type) {
                    this.saving = true;
                    const form = e.target;
                    const formData = new FormData(form);
                    const data = Object.fromEntries(formData.entries());
                    const url = form.action;

                    try {
                        const response = await fetch(url, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(data)
                        });

                        if (response.ok) {
                            if (type === 'type') {
                                document.getElementById('type-name-' + this.editingType).innerText = data.name;
                                this.editingType = null;
                            } else if (type === 'point') {
                                document.getElementById('point-name-' + this.editingPoint).innerText = data.name;
                                this.editingPoint = null;
                            } else if (type === 'gfd') {
                                document.getElementById('gfd-name-' + this.editingGfd).innerText = data.name;
                                this.editingGfd = null;
                            }
                        }
                    } catch (error) {
                        console.error('Update failed:', error);
                    } finally {
                        this.saving = false;
                    }
                },
                async ajaxDelete(e, elementId) {
                    if (!confirm('Yakin ingin menghapus?')) return;

                    const form = e.currentTarget;
                    const url = form.action;

                    try {
                        const response = await fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            document.getElementById(elementId).remove();
                        }
                    } catch (error) {
                        console.error('Delete failed:', error);
                    }
                },
                async ajaxStore(e, targetContainerId) {
                    this.saving = true;
                    const form = e.target;
                    const formData = new FormData(form);
                    const url = form.action;

                    try {
                        const response = await fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        if (response.ok) {
                            const data = await response.json();
                            const container = document.getElementById(targetContainerId);

                            // If it's a Point, insert before the form
                            if (targetContainerId.startsWith('points-list-')) {
                                form.insertAdjacentHTML('beforebegin', data.html);
                            } else {
                                container.insertAdjacentHTML('beforeend', data.html);
                            }

                            form.reset();
                        }
                    } catch (error) {
                        console.error('Store failed:', error);
                    } finally {
                        this.saving = false;
                    }
                },
                async reorder(url, containerId) {
                    const container = document.getElementById(containerId);
                    const ids = Array.from(container.children)
                        .filter(el => el.dataset.id)
                        .map(el => el.dataset.id);

                    try {
                        await fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ ids })
                        });
                    } catch (error) {
                        console.error('Reorder failed:', error);
                    }
                }
            }" x-init="
                // Initialize Sortable for Type Signals
                new Sortable(document.getElementById('type-signal-list'), {
                    animation: 150,
                    handle: '.drag-handle',
                    onEnd: () => $data.reorder('{{ route('configuration.type-signals.reorder') }}', 'type-signal-list')
                });

                // Initialize Sortable for GFD Items
                new Sortable(document.getElementById('gfd-item-list'), {
                    animation: 150,
                    handle: '.drag-handle',
                    onEnd: () => $data.reorder('{{ route('configuration.gfd-items.reorder') }}', 'gfd-item-list')
                });

                // Initialize Sortable for Points in each Signal
                @foreach($typeSignals as $signal)
                    new Sortable(document.getElementById('points-list-{{ $signal->id }}'), {
                        animation: 150,
                        handle: '.drag-handle',
                        draggable: '[data-id]', // Only reorder items with data-id (exclude add form)
                        onEnd: () => $data.reorder('{{ route('configuration.points.reorder') }}', 'points-list-{{ $signal->id }}')
                    });
                @endforeach
            ">

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        @endpush

        {{-- RC Type Signals & Points --}}
        <div class="bg-white rounded-xl p-8" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-on-surface">RC Type Signals</h2>
                    <p class="text-sm text-on-surface/40 mt-1">Master data tipe sinyal Remote Control</p>
                </div>
            </div>

            {{-- Add Form --}}
            <form method="POST" action="{{ route('configuration.type-signals.store') }}" class="flex items-center gap-3 mb-6" @submit.prevent="ajaxStore($event, 'type-signal-list')">
                @csrf
                <input name="name" type="text" required placeholder="Nama type signal baru..." class="flex-1 bg-surface-container-low rounded-md py-2.5 px-4 text-sm border-none">
                <button type="submit" class="h-10 px-5 bg-primary hover:bg-primary-dark text-white rounded-md font-bold text-xs uppercase tracking-widest transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm" x-show="!saving">add</span>
                    <span class="material-symbols-outlined text-sm animate-spin" x-show="saving">sync</span>
                    Tambah
                </button>
            </form>

            {{-- List --}}
            <div id="type-signal-list">
                @foreach($typeSignals as $signal)
                    @include('configuration.partials.type-signal-row', ['signal' => $signal])
                @endforeach
            </div>
        </div>

        {{-- GFD Inspection Items --}}
        <div class="bg-white rounded-xl p-8" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-on-surface">Uraian Pemeriksaan GFD</h2>
                <p class="text-sm text-on-surface/40 mt-1">Item pemeriksaan Ground Fault Detector</p>
            </div>

            {{-- Add Form --}}
            <form method="POST" action="{{ route('configuration.gfd-items.store') }}" class="flex items-center gap-3 mb-6" @submit.prevent="ajaxStore($event, 'gfd-item-list')">
                @csrf
                <input name="name" type="text" required placeholder="Nama item pemeriksaan baru..." class="flex-1 bg-surface-container-low rounded-md py-2.5 px-4 text-sm border-none">
                <button type="submit" class="h-10 px-5 bg-secondary hover:bg-secondary/90 text-white rounded-md font-bold text-xs uppercase tracking-widest transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm" x-show="!saving">add</span>
                    <span class="material-symbols-outlined text-sm animate-spin" x-show="saving">sync</span>
                    Tambah
                </button>
            </form>

            <div class="space-y-1" id="gfd-item-list">
                @foreach($gfdItems as $item)
                    @include('configuration.partials.gfd-item-row', ['item' => $item])
                @endforeach
            </div>
        </div>
    </div>
@endsection