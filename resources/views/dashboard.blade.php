@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    @php $user = auth()->user(); @endphp

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @if($user->isAdmin())
            <x-stat-card label="Total User" :value="$totalUsers" icon="group" borderColor="primary" />
            <x-stat-card label="User Aktif" :value="$activeUsers" icon="check_circle" borderColor="primary" />
            <x-stat-card label="Menunggu Verifikasi" :value="$pendingVerification" icon="pending" borderColor="secondary" />
            <x-stat-card label="Menunggu Review" :value="$pendingReview" icon="rate_review" borderColor="tertiary-container" />
        @elseif($user->isPimpinan())
            <x-stat-card label="Siap Review" :value="$verifiedReports" icon="fact_check" borderColor="secondary" />
            <x-stat-card label="Sudah Direview" :value="$reviewedReports" icon="verified" borderColor="primary" />
            <x-stat-card label="Total Laporan" :value="$totalReports" icon="description" borderColor="primary" />
        @else
            <x-stat-card label="Draft" :value="$draftReports" icon="edit_note" borderColor="primary" />
            <x-stat-card label="Disubmit" :value="$submittedReports" icon="send" borderColor="primary" />
            <x-stat-card label="Ditolak" :value="$rejectedReports" icon="cancel" borderColor="secondary" />
            <x-stat-card label="Total Laporan" :value="$totalReports" icon="description" borderColor="primary" />
        @endif
    </div>

    {{-- Charts Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        {{-- Activity Trend --}}
        <div class="lg:col-span-2 bg-white p-8 rounded-xl shadow-sm overflow-hidden">
            <h3 class="text-sm font-bold text-on-surface uppercase tracking-widest mb-6">Tren Aktivitas (30 Hari Terakhir)</h3>
            <div class="relative h-[300px] w-full">
                <canvas id="activityChart"></canvas>
            </div>
        </div>

        {{-- Type Distribution --}}
        <div class="bg-white p-8 rounded-xl shadow-sm flex flex-col overflow-hidden">
            <h3 class="text-sm font-bold text-on-surface uppercase tracking-widest mb-6">Distribusi Tipe</h3>
            <div class="flex-1 relative min-h-[250px] w-full">
                <canvas id="typeChart"></canvas>
            </div>
        </div>
    </div>

    @if($user->isAdmin() && count($topPegawai))
    {{-- Admin Only Performance --}}
    <div class="bg-white p-8 rounded-xl shadow-sm mb-8 overflow-hidden">
        <h3 class="text-sm font-bold text-on-surface uppercase tracking-widest mb-6">Top 5 Pegawai Teraktif</h3>
        <div class="relative h-[250px] w-full">
            <canvas id="performanceChart"></canvas>
        </div>
    </div>
    @endif

    {{-- Main Content Area --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Recent Activity Table --}}
        <div class="lg:col-span-2 bg-white p-8 rounded-xl" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-xl font-bold text-on-surface">Aktivitas Terkini</h2>
                    <p class="text-sm text-on-surface/40 mt-1">Laporan pekerjaan terakhir</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-[10px] font-bold text-on-surface/40 uppercase tracking-widest" style="border-bottom: 1px solid rgba(203,196,207,0.15);">
                            <th class="pb-4">ID</th>
                            @if(!$user->isPegawai())
                                <th class="pb-4">Pegawai</th>
                            @endif
                            <th class="pb-4">Tipe</th>
                            <th class="pb-4">Gardu</th>
                            <th class="pb-4">Alamat</th>
                            <th class="pb-4">Tanggal</th>
                            <th class="pb-4">Admin</th>
                            <th class="pb-4">Pimpinan</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($recentReports as $report)
                            <tr class="table-row-hover cursor-pointer" onclick="window.location='{{ route('reports.show', $report) }}'" style="border-bottom: 1px solid rgba(203,196,207,0.08);">
                                <td class="py-4 font-mono text-xs text-on-surface/50">#RPT-{{ str_pad($report->id, 5, '0', STR_PAD_LEFT) }}</td>
                                @if(!$user->isPegawai())
                                    <td class="py-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-sm bg-primary/10 text-[10px] flex items-center justify-center font-bold text-primary">{{ $report->user->initials }}</div>
                                            <span class="font-semibold text-on-surface text-xs whitespace-nowrap">{{ $report->user->name }}</span>
                                        </div>
                                    </td>
                                @endif
                                <td class="py-4">
                                    <span class="text-[10px] font-bold uppercase tracking-widest {{ $report->isRc() ? 'text-primary' : 'text-secondary' }}">{{ $report->type_label }}</span>
                                </td>
                                <td class="py-4 text-on-surface/70 font-medium">{{ $report->gardu }}</td>
                                <td class="py-4 text-on-surface/50 text-xs max-w-[150px] truncate">{{ $report->address }}</td>
                                <td class="py-4 text-on-surface/50 text-xs whitespace-nowrap">{{ $report->date->format('d M Y') }}</td>
                                <td class="py-4"><x-status-badge :status="$report->status_admin" /></td>
                                <td class="py-4"><x-status-badge :status="$report->status_pimpinan" /></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ $user->isPegawai() ? 5 : 6 }}" class="py-12 text-center text-on-surface/30">
                                    <span class="material-symbols-outlined text-4xl mb-2 block">inbox</span>
                                    <p class="text-sm font-medium">Belum ada laporan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Side Panel --}}
        <div class="space-y-6">
            {{-- Quick Info --}}
            <div class="bg-white p-8 rounded-xl" style="box-shadow: 0px 1px 3px rgba(45,27,77,0.06);">
                <h2 class="text-xl font-bold text-on-surface mb-6">Ringkasan</h2>

                @if($user->isAdmin())
                    <div class="space-y-4">
                        <div class="p-4 bg-primary/5 rounded-lg" style="border-left: 2px solid #7C3AED;">
                            <p class="text-[10px] font-bold text-primary mb-1 uppercase tracking-widest">Perlu Verifikasi</p>
                            <p class="text-sm text-on-surface/80">{{ $pendingVerification }} laporan menunggu verifikasi Anda.</p>
                        </div>
                        <div class="p-4 bg-secondary/5 rounded-lg" style="border-left: 2px solid #F97316;">
                            <p class="text-[10px] font-bold text-secondary mb-1 uppercase tracking-widest">Menunggu Review</p>
                            <p class="text-sm text-on-surface/80">{{ $pendingReview }} laporan sudah diverifikasi, menunggu review pimpinan.</p>
                        </div>
                        <div class="p-4 bg-surface-container rounded-lg">
                            <p class="text-[10px] font-bold text-on-surface-variant mb-1 uppercase tracking-widest">User Nonaktif</p>
                            <p class="text-sm text-on-surface/80">{{ $inactiveUsers }} user saat ini dinonaktifkan.</p>
                        </div>
                    </div>
                @elseif($user->isPimpinan())
                    <div class="space-y-4">
                        <div class="p-4 bg-secondary/5 rounded-lg" style="border-left: 2px solid #F97316;">
                            <p class="text-[10px] font-bold text-secondary mb-1 uppercase tracking-widest">Siap Review</p>
                            <p class="text-sm text-on-surface/80">{{ $verifiedReports }} laporan terverifikasi menunggu review Anda.</p>
                        </div>
                        <div class="p-4 bg-primary/5 rounded-lg" style="border-left: 2px solid #7C3AED;">
                            <p class="text-[10px] font-bold text-primary mb-1 uppercase tracking-widest">Selesai</p>
                            <p class="text-sm text-on-surface/80">{{ $reviewedReports }} laporan sudah Anda review.</p>
                        </div>
                    </div>
                @else
                    <div class="space-y-4">
                        @if($rejectedReports > 0)
                            <div class="p-4 bg-error-bg rounded-lg" style="border-left: 2px solid #DC2626;">
                                <p class="text-[10px] font-bold text-error mb-1 uppercase tracking-widest">Ditolak</p>
                                <p class="text-sm text-on-surface/80">{{ $rejectedReports }} laporan ditolak, perbaiki dan submit ulang.</p>
                            </div>
                        @endif
                        <div class="p-4 bg-primary/5 rounded-lg" style="border-left: 2px solid #7C3AED;">
                            <p class="text-[10px] font-bold text-primary mb-1 uppercase tracking-widest">Info</p>
                            <p class="text-sm text-on-surface/80">Anda memiliki {{ $draftReports }} draft laporan. Jangan lupa untuk submit.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Activity Chart
        const trendsData = @js($trends);
        const activityCtx = document.getElementById('activityChart').getContext('2d');
        new Chart(activityCtx, {
            type: 'line',
            data: {
                labels: Object.keys(trendsData),
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: Object.values(trendsData),
                    borderColor: '#7C3AED',
                    backgroundColor: 'rgba(124, 58, 237, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: { 
                            stepSize: 1, 
                            precision: 0,
                            color: 'rgba(45,27,77,0.4)' 
                        },
                        grid: { color: 'rgba(45,27,77,0.05)' }
                    },
                    x: { 
                        ticks: { color: 'rgba(45,27,77,0.4)' },
                        grid: { display: false }
                    }
                }
            }
        });

        // Type Chart
        const distData = @js($typeDistribution);
        const typeCtx = document.getElementById('typeChart').getContext('2d');
        new Chart(typeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Remote Control', 'Ground Fault Detector'],
                datasets: [{
                    data: [distData.rc || 0, distData.gfd || 0],
                    backgroundColor: ['#7C3AED', '#F97316'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { size: 11, weight: 'bold' },
                            color: 'rgba(45,27,77,0.6)'
                        }
                    }
                },
                cutout: '75%'
            }
        });

        @if($user->isAdmin() && count($topPegawai))
        // Performance Chart
        const performanceData = @js($topPegawai);
        const perfCtx = document.getElementById('performanceChart').getContext('2d');
        new Chart(perfCtx, {
            type: 'bar',
            data: {
                labels: performanceData.map(u => u.name),
                datasets: [{
                    label: 'Total Laporan',
                    data: performanceData.map(u => u.task_reports_count),
                    backgroundColor: '#7C3AED',
                    borderRadius: 6,
                    barThickness: 20
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { 
                        beginAtZero: true, 
                        grid: { display: false }, 
                        ticks: { 
                            stepSize: 1,
                            precision: 0,
                            color: 'rgba(45,27,77,0.4)' 
                        } 
                    },
                    y: { grid: { display: false }, ticks: { color: 'rgba(45,27,77,0.6)', font: { weight: 'bold' } } }
                }
            }
        });
        @endif
    });
</script>
@endpush