@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan Pekerjaan')

@section('content')
<div class="bg-white rounded-xl p-8" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-xl font-bold text-on-surface">Daftar Laporan</h2>
            <p class="text-sm text-on-surface/40 mt-1">Semua laporan pekerjaan</p>
        </div>
        <div class="flex items-center gap-3">
            @if(auth()->user()->isPegawai())
            <a href="{{ route('reports.create') }}"
               class="py-2.5 px-5 bg-primary hover:bg-primary-dark text-white rounded-md transition-all font-bold flex items-center gap-2 active:scale-[0.98] text-xs uppercase tracking-widest">
                <span class="material-symbols-outlined text-sm">add</span>
                Buat Laporan
            </a>
            @endif
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('reports.index') }}" class="flex flex-wrap gap-3 mb-6">
        <div class="relative flex-1 min-w-[200px]">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface/30 text-lg">search</span>
            <input name="search" value="{{ request('search') }}" type="text"
                class="w-full bg-surface-container-low rounded-md py-2.5 pl-10 pr-4 text-sm border-none" placeholder="Cari gardu, alamat...">
        </div>

        {{-- Type Filter --}}
        <div class="relative" x-data="{ open: false, selected: @js((array)request('type')) }">
            <button type="button" @click="open = !open" 
                class="bg-surface-container-low rounded-md py-2.5 px-4 text-sm border-none min-w-[180px] flex items-center justify-between gap-2 text-left">
                <span class="truncate" x-text="selected.length ? selected.length + ' Tipe Terpilih' : 'Semua Tipe'"></span>
                <span class="material-symbols-outlined text-sm transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="open" @click.away="open = false" x-cloak
                class="absolute left-0 mt-2 w-full bg-white rounded-xl shadow-xl border border-surface-container z-50 p-2 space-y-1">
                <label class="flex items-center gap-2 p-2 hover:bg-surface-container-low rounded-md cursor-pointer transition-colors">
                    <input type="checkbox" name="type[]" value="rc" x-model="selected" class="rounded border-surface-container text-primary focus:ring-primary/20">
                    <span class="text-xs font-semibold text-on-surface">Remote Control</span>
                </label>
                <label class="flex items-center gap-2 p-2 hover:bg-surface-container-low rounded-md cursor-pointer transition-colors">
                    <input type="checkbox" name="type[]" value="gfd" x-model="selected" class="rounded border-surface-container text-primary focus:ring-primary/20">
                    <span class="text-xs font-semibold text-on-surface">Ground Fault Detector</span>
                </label>
            </div>
        </div>

        {{-- Status Filter --}}
        <div class="relative" x-data="{ open: false, selected: @js((array)request('status')) }">
            <button type="button" @click="open = !open" 
                class="bg-surface-container-low rounded-md py-2.5 px-4 text-sm border-none min-w-[160px] flex items-center justify-between gap-2 text-left">
                <span class="truncate" x-text="selected.length ? selected.length + ' Status Terpilih' : 'Semua Status'"></span>
                <span class="material-symbols-outlined text-sm transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="open" @click.away="open = false" x-cloak
                class="absolute left-0 mt-2 w-full min-w-[200px] bg-white rounded-xl shadow-xl border border-surface-container z-50 p-2 space-y-1">
                @php
                    $user = auth()->user();
                    if($user->isPimpinan()) {
                        $availableStatuses = [
                            'terverifikasi' => 'Menunggu',
                            'direview' => 'Direview',
                            'ditolak' => 'Ditolak'
                        ];
                    } else {
                        $availableStatuses = [
                            'draft' => 'Draft',
                            'disubmit' => 'Disubmit',
                            'terverifikasi' => 'Terverifikasi',
                            'ditolak' => 'Ditolak',
                            'direview' => 'Selesai'
                        ];
                    }
                @endphp
                @foreach($availableStatuses as $value => $label)
                <label class="flex items-center gap-2 p-2 hover:bg-surface-container-low rounded-md cursor-pointer transition-colors">
                    <input type="checkbox" name="status[]" value="{{ $value }}" x-model="selected" class="rounded border-surface-container text-primary focus:ring-primary/20">
                    <span class="text-xs font-semibold text-on-surface">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Status Admin Filter --}}
        <div class="relative" x-data="{ open: false, selected: @js((array)request('status_admin')) }">
            <button type="button" @click="open = !open" 
                class="bg-surface-container-low rounded-md py-2.5 px-4 text-sm border-none min-w-[160px] flex items-center justify-between gap-2 text-left">
                <span class="truncate" x-text="selected.length ? selected.length + ' Admin Terpilih' : 'Status Admin'"></span>
                <span class="material-symbols-outlined text-sm transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="open" @click.away="open = false" x-cloak
                class="absolute left-0 mt-2 w-full min-w-[200px] bg-white rounded-xl shadow-xl border border-surface-container z-50 p-2 space-y-1">
                @foreach(['Menunggu', 'Disetujui', 'Ditolak'] as $value)
                <label class="flex items-center gap-2 p-2 hover:bg-surface-container-low rounded-md cursor-pointer transition-colors">
                    <input type="checkbox" name="status_admin[]" value="{{ $value }}" x-model="selected" class="rounded border-surface-container text-primary focus:ring-primary/20">
                    <span class="text-xs font-semibold text-on-surface">{{ $value }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Status Pimpinan Filter --}}
        <div class="relative" x-data="{ open: false, selected: @js((array)request('status_pimpinan')) }">
            <button type="button" @click="open = !open" 
                class="bg-surface-container-low rounded-md py-2.5 px-4 text-sm border-none min-w-[160px] flex items-center justify-between gap-2 text-left">
                <span class="truncate" x-text="selected.length ? selected.length + ' Pimpinan Terpilih' : 'Status Pimpinan'"></span>
                <span class="material-symbols-outlined text-sm transition-transform" :class="open ? 'rotate-180' : ''">expand_more</span>
            </button>
            <div x-show="open" @click.away="open = false" x-cloak
                class="absolute left-0 mt-2 w-full min-w-[200px] bg-white rounded-xl shadow-xl border border-surface-container z-50 p-2 space-y-1">
                @foreach(['Menunggu', 'Disetujui', 'Ditolak'] as $value)
                <label class="flex items-center gap-2 p-2 hover:bg-surface-container-low rounded-md cursor-pointer transition-colors">
                    <input type="checkbox" name="status_pimpinan[]" value="{{ $value }}" x-model="selected" class="rounded border-surface-container text-primary focus:ring-primary/20">
                    <span class="text-xs font-semibold text-on-surface">{{ $value }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="py-2.5 px-5 bg-surface-container-high hover:bg-surface-container-highest text-on-surface rounded-md font-bold text-xs uppercase tracking-widest transition-all">
            Filter
        </button>
        <a href="{{ route('reports.index') }}" class="py-2.5 px-5 text-on-surface-variant hover:text-on-surface font-bold text-xs uppercase tracking-widest transition-colors flex items-center gap-2">
            Reset
        </a>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest" style="border-bottom: 1px solid rgba(203,196,207,0.15);">
                    <th class="pb-4">ID</th>
                    @if(!auth()->user()->isPegawai())
                    <th class="pb-4">Pegawai</th>
                    @endif
                    <th class="pb-4">Tipe</th>
                    <th class="pb-4">Gardu</th>
                    <th class="pb-4">Alamat</th>
                    <th class="pb-4">Tanggal</th>
                    <th class="pb-4">Status Admin</th>
                    <th class="pb-4">Status Pimpinan</th>
                    <th class="pb-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($reports as $report)
                <tr class="table-row-hover" style="border-bottom: 1px solid rgba(203,196,207,0.08);">
                    <td class="py-4 font-mono text-xs text-on-surface/50">#RPT-{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                    @if(!auth()->user()->isPegawai())
                    <td class="py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-sm bg-primary/10 text-[10px] flex items-center justify-center font-bold text-primary">{{ $report->user->initials }}</div>
                            <span class="font-semibold text-on-surface text-xs">{{ $report->user->name }}</span>
                        </div>
                    </td>
                    @endif
                    <td class="py-4">
                        <span class="text-[10px] font-bold uppercase tracking-widest {{ $report->isRc() ? 'text-primary' : 'text-secondary' }}">{{ $report->type_label }}</span>
                    </td>
                    <td class="py-4 text-on-surface/70 font-medium">{{ $report->gardu }}</td>
                    <td class="py-4 text-on-surface/50 text-xs max-w-[200px] truncate">{{ $report->address }}</td>
                    <td class="py-4 text-on-surface/50 text-xs">{{ $report->date->format('d M Y') }}</td>
                    <td class="py-4"><x-status-badge :status="$report->status_admin" /></td>
                    <td class="py-4"><x-status-badge :status="$report->status_pimpinan" /></td>
                    <td class="py-4">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('reports.show', $report) }}" class="p-1.5 hover:bg-surface-container rounded transition-colors" title="Lihat">
                                <span class="material-symbols-outlined text-base text-on-surface-variant">visibility</span>
                            </a>
                            @if(auth()->user()->isPegawai() && $report->canBeEdited())
                            <a href="{{ route('reports.edit', $report) }}" class="p-1.5 hover:bg-surface-container rounded transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-base text-on-surface-variant">edit</span>
                            </a>
                            @endif
                            @if(auth()->user()->isPegawai() && $report->canBeDeleted())
                            <form method="POST" action="{{ route('reports.destroy', $report) }}" class="inline"
                                  onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 hover:bg-error-bg rounded transition-colors" title="Hapus">
                                    <span class="material-symbols-outlined text-base text-error">delete</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="py-12 text-center text-on-surface/30">
                        <span class="material-symbols-outlined text-4xl mb-2 block">inbox</span>
                        <p class="text-sm font-medium">Belum ada laporan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $reports->links() }}</div>
</div>
@endsection
