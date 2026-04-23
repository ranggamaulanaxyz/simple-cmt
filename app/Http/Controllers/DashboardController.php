<?php

namespace App\Http\Controllers;

use App\Models\TaskReport;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isPimpinan()) {
            return $this->pimpinanDashboard();
        } else {
            return $this->pegawaiDashboard($user);
        }
    }

    private function getTrendData($query)
    {
        return $query->clone()
            ->where('date', '>=', now()->subDays(30))
            ->selectRaw('date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->mapWithKeys(fn($item) => [$item->date->format('Y-m-d') => $item->count]);
    }

    private function adminDashboard()
    {
        $totalUsers = User::count();
        $activeUsers = User::where('is_active', true)->count();
        $inactiveUsers = User::where('is_active', false)->count();

        $pendingVerification = TaskReport::byStatus('disubmit')->count();
        $pendingReview = TaskReport::byStatus('terverifikasi')->count();
        $totalReports = TaskReport::where('status', '!=', 'draft')->count();
        $reviewedReports = TaskReport::byStatus('direview')->count();

        $recentReports = TaskReport::with('user')
            ->where('status', '!=', 'draft')
            ->latest()
            ->take(10)
            ->get();

        // Chart Data
        $baseQuery = TaskReport::where('status', '!=', 'draft');
        $trends = $this->getTrendData($baseQuery);
        $typeDistribution = (clone $baseQuery)->selectRaw('type, count(*) as count')->groupBy('type')->pluck('count', 'type');
        $topPegawai = User::where('role', 'pegawai')
            ->withCount(['taskReports' => function($query) {
                $query->where('status', '!=', 'draft');
            }])
            ->orderByDesc('task_reports_count')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalUsers', 'activeUsers', 'inactiveUsers',
            'pendingVerification', 'pendingReview', 'totalReports', 'reviewedReports',
            'recentReports', 'trends', 'typeDistribution', 'topPegawai'
        ));
    }

    private function pimpinanDashboard()
    {
        $verifiedReports = TaskReport::where('status_admin', 'Disetujui')->where('status', 'terverifikasi')->count();
        $reviewedReports = TaskReport::where('status_admin', 'Disetujui')->where('status', 'direview')->count();
        $totalReports = TaskReport::where('status_admin', 'Disetujui')->count();

        $recentReports = TaskReport::with('user')
            ->where('status_admin', 'Disetujui')
            ->latest()
            ->take(10)
            ->get();

        // Chart Data
        $baseQuery = TaskReport::where('status_admin', 'Disetujui');
        $trends = $this->getTrendData($baseQuery);
        $typeDistribution = (clone $baseQuery)
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        return view('dashboard', compact(
            'verifiedReports', 'reviewedReports', 'totalReports',
            'recentReports', 'trends', 'typeDistribution'
        ));
    }

    private function pegawaiDashboard(User $user)
    {
        $draftReports = TaskReport::forUser($user->id)->byStatus('draft')->count();
        $submittedReports = TaskReport::forUser($user->id)->byStatus('disubmit')->count();
        $rejectedReports = TaskReport::forUser($user->id)->byStatus('ditolak')->count();
        $totalReports = TaskReport::forUser($user->id)->count();

        $recentReports = TaskReport::forUser($user->id)
            ->latest()
            ->take(10)
            ->get();

        // Chart Data
        $trends = $this->getTrendData(TaskReport::forUser($user->id));
        $typeDistribution = TaskReport::forUser($user->id)
            ->selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        return view('dashboard', compact(
            'draftReports', 'submittedReports', 'rejectedReports', 'totalReports',
            'recentReports', 'trends', 'typeDistribution'
        ));
    }
}
