@extends('layouts.app')
@section('title', 'Detail Laporan')
@section('page-title', 'Detail Laporan')

@section('content')
    @php $user = auth()->user(); @endphp

    <div x-data>
        <div class="space-y-8">

            {{-- Header & Actions --}}
            <div class="flex flex-wrap justify-between items-start gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <h2 class="text-2xl font-extrabold text-on-surface">#RPT-{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</h2>
                        <div class="flex items-center gap-2 ml-2">
                            <span class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest">Admin</span>
                            <x-status-badge :status="$report->status_admin" />
                        </div>
                        <div class="flex items-center gap-2 pl-3 border-l border-outline-variant/30">
                            <span class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest">Pimpinan</span>
                            <x-status-badge :status="$report->status_pimpinan" />
                        </div>
                    </div>
                    <p class="text-sm text-on-surface/50">{{ $report->type_label }} — {{ $report->date->format('d M Y') }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    @if($user->isPegawai() && $report->canBeSubmitted() && $report->user_id === $user->id)
                        <form method="POST" action="{{ route('reports.submit', $report) }}">
                            @csrf
                            <button type="submit" class="py-2.5 px-5 bg-primary hover:bg-primary-dark text-white rounded-md font-bold text-xs uppercase tracking-widest transition-all flex items-center gap-2 active:scale-[0.98]">
                                <span class="material-symbols-outlined text-sm">send</span> Submit
                            </button>
                        </form>
                    @endif

                    @if($user->isPegawai() && $report->canBeCancelled() && $report->user_id === $user->id)
                        <form method="POST" action="{{ route('reports.cancel', $report) }}">
                            @csrf
                            <button type="submit" class="py-2.5 px-5 text-warning hover:bg-warning-bg rounded-md font-bold text-xs uppercase tracking-widest transition-all flex items-center gap-2" style="border: 1px solid rgba(217,119,6,0.2);">
                                <span class="material-symbols-outlined text-sm">undo</span> Batalkan
                            </button>
                        </form>
                    @endif

                    @if($user->isPegawai() && $report->canBeEdited() && $report->user_id === $user->id)
                        <a href="{{ route('reports.edit', $report) }}" class="py-2.5 px-5 text-primary hover:bg-primary/5 rounded-md font-bold text-xs uppercase tracking-widest transition-all flex items-center gap-2" style="border: 1px solid rgba(124,58,237,0.2);">
                            <span class="material-symbols-outlined text-sm">edit</span> Edit
                        </a>
                    @endif

                    @if($user->isAdmin() && $report->canBeVerified())
                        <form method="POST" action="{{ route('reports.verify', $report) }}">
                            @csrf
                            <button type="submit" class="py-2.5 px-5 bg-success hover:bg-success/90 text-white rounded-md font-bold text-xs uppercase tracking-widest transition-all flex items-center gap-2 active:scale-[0.98]">
                                <span class="material-symbols-outlined text-sm">verified</span> Verifikasi
                            </button>
                        </form>
                    @endif

                    @if(($user->isAdmin() || $user->isPimpinan()) && $report->canBeRejected())
                        <button type="button" @click="$refs.rejectModal.showModal()" class="py-2.5 px-5 text-error hover:bg-error-bg rounded-md font-bold text-xs uppercase tracking-widest transition-all flex items-center gap-2" style="border: 1px solid rgba(220,38,38,0.2);">
                            <span class="material-symbols-outlined text-sm">cancel</span> Tolak
                        </button>
                    @endif

                    @if($user->isPimpinan() && $report->canBeReviewed())
                        <form method="POST" action="{{ route('reports.review', $report) }}">
                            @csrf
                            <button type="submit" class="py-2.5 px-5 bg-primary hover:bg-primary-dark text-white rounded-md font-bold text-xs uppercase tracking-widest transition-all flex items-center gap-2 active:scale-[0.98]">
                                <span class="material-symbols-outlined text-sm">rate_review</span> Review
                            </button>
                        </form>
                    @endif

                    @if($user->isAdmin() || $user->isPimpinan())
                        <a href="{{ route('export.reports.single', $report) }}" class="py-2.5 px-5 text-primary hover:bg-primary/5 rounded-md font-bold text-xs uppercase tracking-widest transition-all flex items-center gap-2" style="border: 1px solid rgba(124,58,237,0.2);">
                            <span class="material-symbols-outlined text-sm">download</span> Export Excel
                        </a>
                        @if($report->isGfd())
                            <a href="{{ route('export.reports.docx', $report) }}" class="py-2.5 px-5 text-primary hover:bg-primary/5 rounded-md font-bold text-xs uppercase tracking-widest transition-all flex items-center gap-2" style="border: 1px solid rgba(124,58,237,0.2);">
                                <span class="material-symbols-outlined text-sm">description</span> Export Word
                            </a>
                        @endif
                    @endif

                    <a href="{{ route('reports.index') }}" class="py-2.5 px-5 text-on-surface-variant hover:text-on-surface font-bold text-xs uppercase tracking-widest transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">arrow_back</span> Kembali
                    </a>
                </div>
            </div>

            {{-- Rejection Note --}}
            @if($report->isRejected() && $report->rejection_note)
                <div class="p-5 bg-error-bg rounded-xl" style="border-left: 3px solid #DC2626;">
                    <p class="text-[10px] font-bold text-error mb-1 uppercase tracking-widest">Catatan Penolakan</p>
                    <p class="text-sm text-on-surface/80">{{ $report->rejection_note }}</p>
                </div>
            @endif

            {{-- General Info --}}
            <div class="bg-white rounded-xl p-8" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
                <h3 class="text-lg font-bold text-on-surface mb-6">Informasi Umum</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-6 gap-x-8">
                    <div>
                        <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest mb-1">Pegawai</p>
                        <p class="text-sm font-semibold text-on-surface">{{ $report->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest mb-1">{{ $report->isGfd() ? 'Nomor Gardu' : 'Gardu' }}</p>
                        <p class="text-sm font-semibold text-on-surface">{{ $report->gardu }}</p>
                    </div>

                    @if($report->isGfd())
                        <div>
                            <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest mb-1">Arah Gardu</p>
                            <p class="text-sm font-semibold text-on-surface">{{ $report->arah_gardu ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest mb-1">Jenis Pekerjaan</p>
                            <p class="text-sm font-semibold text-on-surface">{{ $report->task_type ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest mb-1">Penyulang</p>
                            <p class="text-sm font-semibold text-on-surface">{{ $report->penyulang ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest mb-1">Gardu Induk</p>
                            <p class="text-sm font-semibold text-on-surface">{{ $report->gardu_induk ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest mb-1">Type Gardu</p>
                            <p class="text-sm font-semibold text-on-surface">{{ $report->type_gardu ?? '-' }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest mb-1">Alamat</p>
                        <p class="text-sm text-on-surface/80">{{ $report->address }}</p>
                    </div>
                    @if($report->latitude && $report->longitude)
                        <div>
                            <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest mb-1">Koordinat</p>
                            <p class="text-sm text-on-surface/80">{{ $report->latitude }}, {{ $report->longitude }}</p>
                        </div>
                    @endif

                    @if($report->isRc() && $report->notes)
                        <div>
                            <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest mb-1">Catatan Umum</p>
                            <p class="text-sm text-on-surface/80">{{ $report->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Denah & SLD Card (Shared) --}}
            @if($report->denah_sld_file)
                <div class="bg-white rounded-xl p-8 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary text-2xl">description</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold text-on-surface uppercase tracking-widest mb-1">{{ $report->isGfd() ? 'Layout dan Denah' : 'Denah Dan SLD Remote Control' }}</h3>
                            <p class="text-[10px] text-on-surface/50 font-medium">Dokumen Gardu Distribusi</p>
                        </div>
                        <a href="{{ Storage::url($report->denah_sld_file) }}" target="_blank" class="px-5 py-2.5 bg-primary text-on-primary rounded-lg text-xs font-bold hover:bg-primary/90 transition-all flex items-center gap-2 shadow-sm">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                            Lihat Dokumen
                        </a>
                    </div>
                </div>
            @endif

            {{-- RC Specific --}}
            @if($report->isRc())
                @if($report->rcDirections->count())
                    <div class="bg-white rounded-xl p-8" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
                        <h3 class="text-lg font-bold text-on-surface mb-6">Arah Remote Control</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest" style="border-bottom: 1px solid rgba(203,196,207,0.15);">
                                        <th class="pb-3">No</th>
                                        <th class="pb-3">Arah</th>
                                        <th class="pb-3">Penyulang</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach($report->rcDirections as $i => $dir)
                                        <tr class="table-row-hover" style="border-bottom: 1px solid rgba(203,196,207,0.08);">
                                            <td class="py-3 text-on-surface/50">{{ $i + 1 }}</td>
                                            <td class="py-3 text-on-surface/80">{{ $dir->arah_remote_control }}</td>
                                            <td class="py-3 text-on-surface/80">{{ $dir->penyulang }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-xl p-8" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
                    <h3 class="text-lg font-bold text-on-surface mb-6">Remote Control Commissioning</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mb-8 pb-8 border-b border-outline-variant/10">
                        <div>
                            <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest mb-1">Arah</p>
                            <p class="text-sm font-semibold text-on-surface">{{ $report->arah ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest mb-1">Lokasi</p>
                            <p class="text-sm font-semibold text-on-surface">{{ $report->lokasi ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest mb-1">Tanggal Commissioning</p>
                            <p class="text-sm font-semibold text-on-surface">{{ $report->date_commissioning?->format('d M Y') ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest mb-1">RTU</p>
                            <p class="text-sm font-semibold text-on-surface">{{ $report->rtu ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest mb-1">Modem</p>
                            <p class="text-sm font-semibold text-on-surface">{{ $report->modem ?? '-' }}</p>
                        </div>
                    </div>

                    @if($report->rcCommissionings->count())
                        <h4 class="text-sm font-bold text-on-surface/70 mb-3 uppercase tracking-widest">Commissioning List</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-[10px] border-collapse">
                                <thead>
                                    <tr class="text-on-surface/40 uppercase tracking-widest border-b border-outline-variant/10">
                                        <th class="pb-3 pr-2" rowspan="2">No</th>
                                        <th class="pb-3 px-2" rowspan="2">Database</th>
                                        <th class="pb-3 px-2" rowspan="2">Type Signal</th>
                                        <th class="pb-3 px-2" rowspan="2">Point</th>
                                        <th class="pb-3 px-2" rowspan="2">Terminal</th>
                                        <th class="pb-3 px-2 text-center border-l border-outline-variant/5" colspan="2">Signaling</th>
                                        <th class="pb-3 px-2 text-center border-l border-outline-variant/5" colspan="3">Control</th>
                                        <th class="pb-3 px-2" rowspan="2">Arah RC</th>
                                        <th class="pb-3 pl-2" rowspan="2">Note</th>
                                    </tr>
                                    <tr class="text-on-surface/30 uppercase tracking-widest border-b border-outline-variant/10">
                                        <th class="py-2 px-1 text-center font-extrabold border-l border-outline-variant/5">GH</th>
                                        <th class="py-2 px-1 text-center font-extrabold">DCC</th>
                                        <th class="py-2 px-1 text-center font-extrabold border-l border-outline-variant/5">RTU</th>
                                        <th class="py-2 px-1 text-center font-extrabold">RELE/PLAT</th>
                                        <th class="py-2 px-1 text-center font-extrabold">LBS</th>
                                    </tr>
                                </thead>
                                <tbody class="text-[11px]">
                                    @foreach($report->rcCommissionings as $i => $comm)
                                        <tr class="table-row-hover border-b border-outline-variant/5">
                                            <td class="py-3 pr-2 text-on-surface/50 font-mono">{{ $i + 1 }}</td>
                                            <td class="py-3 px-2 font-medium">{{ $comm->database_field ?? '-' }}</td>
                                            <td class="py-3 px-2">
                                                <span class="px-2 py-0.5 bg-primary/5 text-primary/60 rounded-sm font-bold uppercase text-[9px]">{{ $comm->point?->typeSignal?->name ?? '-' }}</span>
                                            </td>
                                            <td class="py-3 px-2 font-semibold text-on-surface">{{ $comm->point?->name ?? '-' }}</td>
                                            <td class="py-3 px-2">{{ $comm->terminal_kubiker ?? '-' }}</td>
                                            {{-- Signaling --}}
                                            <td class="py-3 px-1 text-center border-l border-outline-variant/5 font-bold">{!! $comm->signaling_gh ? '<span class="text-success text-sm">✓</span>' : '<span class="text-on-surface/10">—</span>' !!}</td>
                                            <td class="py-3 px-1 text-center font-bold">{!! $comm->signaling_dcc ? '<span class="text-success text-sm">✓</span>' : '<span class="text-on-surface/10">—</span>' !!}</td>
                                            {{-- Control --}}
                                            <td class="py-3 px-1 text-center border-l border-outline-variant/5 font-bold">{!! $comm->control_dcc_rtu ? '<span class="text-success text-sm">✓</span>' : '<span class="text-on-surface/10">—</span>' !!}</td>
                                            <td class="py-3 px-1 text-center font-bold">{!! $comm->control_dcc_rele_plat ? '<span class="text-success text-sm">✓</span>' : '<span class="text-on-surface/10">—</span>' !!}</td>
                                            <td class="py-3 px-1 text-center font-bold">{!! $comm->control_dcc_lbs ? '<span class="text-success text-sm">✓</span>' : '<span class="text-on-surface/10">—</span>' !!}</td>
                                            <td class="py-3 px-2">
                                                <div class="font-medium text-on-surface">{{ $comm->direction->arah_remote_control ?? '-' }}</div>
                                                <div class="text-[10px] text-on-surface/50">{{ $comm->direction->penyulang ?? '-' }}</div>
                                            </td>
                                            <td class="py-3 pl-1 text-center">
                                                @if($comm->note)
                                                    <span class="px-2 py-0.5 bg-success/10 text-success text-[9px] font-bold rounded-sm uppercase tracking-wider">OK</span>
                                                @else
                                                    <span class="px-2 py-0.5 bg-error/10 text-error text-[9px] font-bold rounded-sm uppercase tracking-wider">NOK</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                @if($report->commissioning_notes)
                    <div class="bg-white rounded-xl p-8" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
                        <h3 class="text-lg font-bold text-on-surface mb-4">Note Commissioning</h3>
                        <div class="p-5 bg-surface-container-low rounded-lg">
                            <p class="text-sm text-on-surface/80 leading-relaxed whitespace-pre-line">{{ $report->commissioning_notes }}</p>
                        </div>
                    </div>
                @endif
            @endif

            {{-- GFD Specific --}}
            @if($report->isGfd())
                <div class="bg-white rounded-xl p-8" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
                    <h3 class="text-lg font-bold text-on-surface mb-6">Data Ground Fault Detector</h3>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                        {{-- GFD Lama --}}
                        <div class="space-y-4">
                            <div class="flex items-center gap-2.5 mb-4">
                                <div class="w-7 h-7 rounded-lg bg-on-surface/5 flex items-center justify-center text-on-surface/40">
                                    <span class="material-symbols-outlined text-[18px]">history</span>
                                </div>
                                <h4 class="text-xs font-bold text-on-surface/60 uppercase tracking-widest">GFD Lama</h4>
                            </div>
                            
                            <div class="divide-y divide-on-surface/5 bg-surface-container-low/30 rounded-xl overflow-hidden border border-on-surface/3">
                                @php
                                    $gfdLamaFields = [
                                        'old_gfd' => ['label' => 'GFD', 'unit' => null],
                                        'old_gfd_type_serial_number' => ['label' => 'Type/SN', 'unit' => null],
                                        'old_gfd_setting_kepekaan_arus' => ['label' => 'Kepekaan Arus', 'unit' => 'A'],
                                        'old_gfd_setting_kepekaan_waktu' => ['label' => 'Kepekaan Waktu', 'unit' => 'ms'],
                                        'old_gfd_setting_waktu' => ['label' => 'Setting Waktu (Flashing)', 'unit' => 'h'],
                                        'old_gfd_injek_arus' => ['label' => 'Injek Arus', 'unit' => 'A'],
                                        'old_gfd_condition' => ['label' => 'Kondisi Akhir', 'unit' => null],
                                    ];
                                @endphp
                                @foreach($gfdLamaFields as $field => $data)
                                    <div class="grid grid-cols-2 py-3 px-4 hover:bg-on-surface/2 transition-colors">
                                        <span class="text-[10px] font-bold text-on-surface/40 uppercase tracking-wider self-center">{{ $data['label'] }}</span>
                                        <div class="flex items-center justify-end min-h-6">
                                            <span class="text-xs font-bold text-on-surface/80 {{ in_array($field, ['old_gfd_type_serial_number', 'old_gfd_setting_kepekaan_arus', 'old_gfd_setting_kepekaan_waktu', 'old_gfd_setting_waktu', 'old_gfd_injek_arus']) ? 'font-mono' : '' }}">
                                                {{ $report->$field ?: '-' }}
                                            </span>
                                            <div class="w-7 flex items-center ml-1.5">
                                                @if($data['unit'] && $report->$field)
                                                    <span class="text-[9px] font-extrabold text-on-surface/20 uppercase tracking-widest">{{ $data['unit'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- GFD Baru --}}
                        <div class="space-y-4">
                            <div class="flex items-center gap-2.5 mb-4">
                                <div class="w-7 h-7 rounded-lg bg-secondary/10 flex items-center justify-center text-secondary">
                                    <span class="material-symbols-outlined text-[18px]">new_releases</span>
                                </div>
                                <h4 class="text-xs font-bold text-secondary/80 uppercase tracking-widest">GFD Baru</h4>
                            </div>

                            <div class="divide-y divide-secondary/10 bg-secondary/2 rounded-xl overflow-hidden border border-secondary/10 shadow-sm">
                                @php
                                    $gfdBaruFields = [
                                        'new_gfd' => ['label' => 'GFD', 'unit' => null],
                                        'new_gfd_type_serial_number' => ['label' => 'Type/SN', 'unit' => null],
                                        'new_gfd_setting_kepekaan_arus' => ['label' => 'Kepekaan Arus', 'unit' => 'A'],
                                        'new_gfd_setting_kepekaan_waktu' => ['label' => 'Kepekaan Waktu', 'unit' => 'ms'],
                                        'new_gfd_setting_waktu' => ['label' => 'Setting Waktu (Flashing)', 'unit' => 'h'],
                                        'new_gfd_injek_arus' => ['label' => 'Injek Arus', 'unit' => 'A'],
                                        'new_gfd_condition' => ['label' => 'Kondisi Akhir', 'unit' => null],
                                    ];
                                @endphp
                                @foreach($gfdBaruFields as $field => $data)
                                    <div class="grid grid-cols-2 py-3 px-4 hover:bg-secondary/3 transition-colors">
                                        <span class="text-[10px] font-bold text-secondary/60 uppercase tracking-wider self-center">{{ $data['label'] }}</span>
                                        <div class="flex items-center justify-end min-h-6">
                                            <span class="text-xs font-bold text-secondary {{ in_array($field, ['new_gfd_type_serial_number', 'new_gfd_setting_kepekaan_arus', 'new_gfd_setting_kepekaan_waktu', 'new_gfd_setting_waktu', 'new_gfd_injek_arus']) ? 'font-mono' : '' }}">
                                                {{ $report->$field ?: '-' }}
                                            </span>
                                            <div class="w-7 flex items-center ml-1.5">
                                                @if($data['unit'] && $report->$field)
                                                    <span class="text-[9px] font-extrabold text-secondary/40 uppercase tracking-widest">{{ $data['unit'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @if($report->gfdInspections->count())
                        <h4 class="text-sm font-bold text-on-surface/70 mt-8 mb-4 uppercase tracking-widest">Uraian Pemeriksaan</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest" style="border-bottom: 1px solid rgba(203,196,207,0.15);">
                                        <th class="pb-3">Item</th>
                                        <th class="pb-3 text-center">Ada</th>
                                        <th class="pb-3 text-center">Tidak Ada</th>
                                        <th class="pb-3 text-center">Rusak</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm">
                                    @foreach($report->gfdInspections as $insp)
                                        <tr class="table-row-hover" style="border-bottom: 1px solid rgba(203,196,207,0.08);">
                                            <td class="py-3 text-on-surface/80">{{ $insp->item->name }}</td>
                                            <td class="py-3 text-center">{!! $insp->ada ? '<span class="text-success font-bold">✓</span>' : '<span class="text-on-surface/20">—</span>' !!}</td>
                                            <td class="py-3 text-center">{!! $insp->tidak_ada ? '<span class="text-warning font-bold">✓</span>' : '<span class="text-on-surface/20">—</span>' !!}</td>
                                            <td class="py-3 text-center">{!! $insp->rusak ? '<span class="text-error font-bold">✓</span>' : '<span class="text-on-surface/20">—</span>' !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                @if($report->notes)
                    <div class="bg-white rounded-xl p-8 shadow-sm">
                        <h3 class="text-sm font-bold text-on-surface uppercase tracking-widest mb-4">Catatan Umum</h3>
                        <div class="p-5 bg-surface-container-low rounded-lg border border-outline-variant/10">
                            <p class="text-sm text-on-surface/80 leading-relaxed whitespace-pre-line">{{ $report->notes }}</p>
                        </div>
                    </div>
                @endif
            @endif

            {{-- Photos --}}
            @if($report->images->count())
                <div class="bg-white rounded-xl p-8" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
                    <h3 class="text-lg font-bold text-on-surface mb-6">Foto Dokumentasi</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($report->images as $image)
                            <div class="relative group">
                                <img src="{{ Storage::url($image->image) }}" alt="{{ $image->description }}" class="w-full h-40 object-cover rounded-lg">
                                @if($image->description)
                                    <p class="text-xs text-on-surface/60 mt-1 truncate">{{ $image->description }}</p>
                                @endif
                                @if($user->isPegawai() && $report->canBeEdited() && $report->user_id === $user->id)
                                    <form method="POST" action="{{ route('reports.delete-image', $image) }}" class="absolute top-2 right-2 hidden group-hover:block">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1 bg-error text-white rounded-full text-xs hover:bg-error/90 transition-colors">
                                            <span class="material-symbols-outlined text-sm">close</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Upload more photos (for editable reports) --}}
            @if($user->isPegawai() && $report->canBeEdited() && $report->user_id === $user->id)
                <div class="bg-white rounded-xl p-8" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
                    <h3 class="text-lg font-bold text-on-surface mb-4">Tambah Foto</h3>
                    <form method="POST" action="{{ route('reports.upload-image', $report) }}" enctype="multipart/form-data" class="flex flex-wrap gap-4 items-end">
                        @csrf
                        <div class="flex-1 min-w-[200px]">
                            <input type="file" name="image" accept="image/*" required class="w-full text-sm text-on-surface/60 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-bold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                        </div>
                        <div class="flex-1 min-w-[200px]">
                            <input type="text" name="description" placeholder="Deskripsi foto..." class="w-full bg-surface-container-low rounded-md py-2.5 px-4 text-sm border-none">
                        </div>
                        <button type="submit" class="py-2.5 px-5 bg-primary hover:bg-primary-dark text-white rounded-md font-bold text-xs transition-all">Upload</button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Reject Modal --}}
        @if(($user->isAdmin() || $user->isPimpinan()) && $report->canBeRejected())
            <dialog x-ref="rejectModal" class="p-0 rounded-xl bg-white max-w-md w-full fixed inset-0 m-auto backdrop:bg-black/20 backdrop:backdrop-blur-sm" style="box-shadow: 0px 24px 48px rgba(45,27,77,0.15);">
                <form method="POST" action="{{ route('reports.reject', $report) }}" class="p-8">
                    @csrf
                    <h3 class="text-lg font-bold text-on-surface mb-4">Tolak Laporan</h3>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-[0.12em] text-on-surface/50 mb-2">Catatan Penolakan</label>
                        <textarea name="rejection_note" rows="4" required class="w-full bg-surface-container-low rounded-md py-3 px-4 text-sm border-none resize-none" placeholder="Alasan penolakan..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="$refs.rejectModal.close()" class="py-2.5 px-5 text-on-surface-variant font-bold text-sm transition-colors">Batal</button>
                        <button type="submit" class="py-2.5 px-5 bg-error hover:bg-error/90 text-white rounded-md font-bold text-sm transition-all">Tolak</button>
                    </div>
                </form>
            </dialog>
        @endif
    </div>
@endsection