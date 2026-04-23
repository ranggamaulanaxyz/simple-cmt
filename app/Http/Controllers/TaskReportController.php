<?php

namespace App\Http\Controllers;

use App\Models\TaskReport;
use App\Models\TaskReportImage;
use App\Models\TaskReportRcDirection;
use App\Models\TaskReportRcCommissioning;
use App\Models\TaskReportGfdInspection;
use App\Models\RcPoint;
use App\Models\GfdInspectionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TaskReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $query = TaskReport::with('user');

        // Role-based filtering
        if ($user->isPegawai()) {
            $query->forUser($user->id);
        } elseif ($user->isAdmin()) {
            $query->where('status', '!=', 'draft');
        } elseif ($user->isPimpinan()) {
            $query->where('status_admin', 'Disetujui');
        }

        // Filters
        if ($status = $request->input('status')) {
            $query->byStatus((array) $status);
        }
        if ($statusAdmin = $request->input('status_admin')) {
            $query->whereIn('status_admin', (array) $statusAdmin);
        }
        if ($statusPimpinan = $request->input('status_pimpinan')) {
            $query->whereIn('status_pimpinan', (array) $statusPimpinan);
        }
        if ($type = $request->input('type')) {
            $query->byType((array) $type);
        }
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('gardu', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        $reports = $query->latest()->paginate(15);
        return view('reports.index', compact('reports'));
    }

    public function create()
    {
        $rcPoints = RcPoint::join('rc_type_signals', 'rc_points.type_signal_id', '=', 'rc_type_signals.id')
            ->orderBy('rc_type_signals.sequence')
            ->orderBy('rc_points.sequence')
            ->select('rc_points.*')
            ->with('typeSignal')
            ->get();
        $gfdItems = GfdInspectionItem::orderBy('sequence')->get();
        return view('reports.create', compact('rcPoints', 'gfdItems'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateReport($request);

        DB::transaction(function () use ($request, $validated) {
            $report = TaskReport::create([
                ...$validated,
                'user_id' => $request->user()->id,
                'status' => 'draft',
            ]);

            if ($request->hasFile('denah_sld_file')) {
                $path = $request->file('denah_sld_file')->store('denah-sld', 'public');
                $report->update(['denah_sld_file' => $path]);
            }
            
            $this->saveRelatedData($request, $report);
        });

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dibuat.');
    }

    public function show(TaskReport $report)
    {
        $this->authorizeView($report);
        $report->load(['user', 'images', 'rcDirections', 'rcCommissionings.point', 'rcCommissionings.direction', 'gfdInspections.item']);
        return view('reports.show', compact('report'));
    }

    public function edit(TaskReport $report)
    {
        $this->authorizeEdit($report);
        $report->load(['images', 'rcDirections', 'rcCommissionings', 'gfdInspections']);
        $rcPoints = RcPoint::join('rc_type_signals', 'rc_points.type_signal_id', '=', 'rc_type_signals.id')
            ->orderBy('rc_type_signals.sequence')
            ->orderBy('rc_points.sequence')
            ->select('rc_points.*')
            ->with('typeSignal')
            ->get();
        $gfdItems = GfdInspectionItem::orderBy('sequence')->get();
        return view('reports.edit', compact('report', 'rcPoints', 'gfdItems'));
    }

    public function update(Request $request, TaskReport $report)
    {
        $this->authorizeEdit($report);
        $validated = $this->validateReport($request, $report);

        DB::transaction(function () use ($request, $report, $validated) {
            $report->update($validated);

            // Delete old related data and re-save
            $report->rcDirections()->delete();
            $report->rcCommissionings()->delete();
            $report->gfdInspections()->delete();

            if ($request->hasFile('denah_sld_file')) {
                // Potential TODO: Delete old file if exists
                $path = $request->file('denah_sld_file')->store('denah-sld', 'public');
                $report->update(['denah_sld_file' => $path]);
            }

            $this->saveRelatedData($request, $report);

            // If was rejected, back to draft
            if ($report->status === 'ditolak') {
                $report->update(['status' => 'draft', 'rejection_note' => null]);
            }
        });

        return redirect()->route('reports.show', $report)
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function submit(TaskReport $report)
    {
        if (!$report->canBeSubmitted()) {
            return back()->with('error', 'Laporan tidak dapat disubmit.');
        }
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }

        if ($report->status_admin === 'Disetujui') {
            $report->update([
                'status' => 'terverifikasi',
                'status_pimpinan' => 'Menunggu',
            ]);
        } else {
            $report->update([
                'status' => 'disubmit',
                'status_admin' => 'Menunggu',
                'status_pimpinan' => 'Menunggu',
            ]);
        }
        return back()->with('success', 'Laporan berhasil disubmit.');
    }

    public function cancel(TaskReport $report)
    {
        if (!$report->canBeCancelled()) {
            return back()->with('error', 'Laporan tidak dapat dibatalkan.');
        }
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }

        $report->update(['status' => 'draft']);
        return back()->with('success', 'Laporan berhasil dibatalkan.');
    }

    public function verify(TaskReport $report)
    {
        if (!$report->canBeVerified()) {
            return back()->with('error', 'Laporan tidak dapat diverifikasi.');
        }

        $report->update([
            'status' => 'terverifikasi',
            'status_admin' => 'Disetujui',
        ]);
        return back()->with('success', 'Laporan berhasil diverifikasi.');
    }

    public function reject(Request $request, TaskReport $report)
    {
        if (!$report->canBeRejected()) {
            return back()->with('error', 'Laporan tidak dapat ditolak.');
        }

        $request->validate(['rejection_note' => 'required|string|max:1000']);
        $updateData = [
            'status' => 'ditolak',
            'rejection_note' => $request->input('rejection_note'),
        ];
        
        if ($request->user()->isPimpinan()) {
            $updateData['status_pimpinan'] = 'Ditolak';
        } else {
            $updateData['status_admin'] = 'Ditolak';
        }

        $report->update($updateData);

        if ($request->user()->isPimpinan()) {
            return redirect()->route('reports.index')->with('success', 'Laporan berhasil ditolak.');
        }
        return back()->with('success', 'Laporan berhasil ditolak.');
    }

    public function review(TaskReport $report)
    {
        if (!$report->canBeReviewed()) {
            return back()->with('error', 'Laporan tidak dapat direview.');
        }

        $report->update([
            'status' => 'direview',
            'status_pimpinan' => 'Disetujui',
        ]);
        return back()->with('success', 'Laporan berhasil direview.');
    }

    public function destroy(TaskReport $report)
    {
        if (!$report->canBeDeleted()) {
            return back()->with('error', 'Laporan tidak dapat dihapus.');
        }
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }

        // Delete images from storage
        foreach ($report->images as $image) {
            Storage::disk('public')->delete($image->image);
        }

        $report->delete();
        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    // Image upload
    public function uploadImage(Request $request, TaskReport $report)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
            'description' => 'nullable|string|max:255',
        ]);

        $path = $request->file('image')->store('report-images', 'public');

        TaskReportImage::create([
            'report_id' => $report->id,
            'image' => $path,
            'description' => $request->input('description'),
        ]);

        return back()->with('success', 'Foto berhasil diunggah.');
    }

    public function deleteImage(TaskReportImage $image)
    {
        Storage::disk('public')->delete($image->image);
        $image->delete();
        return back()->with('success', 'Foto berhasil dihapus.');
    }

    // Private helpers
    private function validateReport(Request $request, ?TaskReport $report = null): array
    {
        $isDraft = $request->input('action') === 'draft';

        $rules = [
            'type' => ['required', Rule::in(['rc', 'gfd'])],
            'date' => ['required', 'date'],
            'gardu' => [$isDraft ? 'nullable' : 'required', 'string', 'max:255'],
            'address' => [$isDraft ? 'nullable' : 'required', 'string'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'arah' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'arah_gardu' => ['nullable', 'string', 'max:255'],
        ];

        if ($request->input('type') === 'rc') {
            $rules['rtu'] = ['nullable', 'string', 'max:255'];
            $rules['modem'] = ['nullable', 'string', 'max:255'];
            $rules['lokasi'] = ['nullable', 'string', 'max:255'];
            $rules['date_commissioning'] = ['nullable', 'date'];
            $rules['denah_sld_file'] = ['nullable', 'file', 'mimes:xls,xlsx', 'max:5120'];
            $rules['commissioning_notes'] = ['nullable', 'string'];
        }

        if ($request->input('type') === 'gfd') {
            $rules['task_type'] = ['nullable', 'string', 'max:255'];
            $rules['penyulang'] = ['nullable', 'string', 'max:255'];
            $rules['gardu_induk'] = ['nullable', 'string', 'max:255'];
            $rules['old_gfd'] = ['nullable', 'string'];
            $rules['old_gfd_type_serial_number'] = ['nullable', 'string'];
            $rules['old_gfd_setting_kepekaan_arus'] = ['nullable', 'string'];
            $rules['old_gfd_setting_kepekaan_waktu'] = ['nullable', 'string'];
            $rules['old_gfd_setting_waktu'] = ['nullable', 'string'];
            $rules['old_gfd_injek_arus'] = ['nullable', 'string'];
            $rules['old_gfd_condition'] = ['nullable', 'string'];
            $rules['new_gfd'] = ['nullable', 'string'];
            $rules['new_gfd_type_serial_number'] = ['nullable', 'string'];
            $rules['new_gfd_setting_kepekaan_arus'] = ['nullable', 'string'];
            $rules['new_gfd_setting_kepekaan_waktu'] = ['nullable', 'string'];
            $rules['new_gfd_setting_waktu'] = ['nullable', 'string'];
            $rules['new_gfd_injek_arus'] = ['nullable', 'string'];
            $rules['new_gfd_condition'] = ['nullable', 'string'];
        }

        return $request->validate($rules);
    }

    private function saveRelatedData(Request $request, TaskReport $report): void
    {
        // RC Directions
        $savedDirections = [];
        if ($request->has('rc_directions')) {
            foreach ($request->input('rc_directions', []) as $index => $dir) {
                if (!empty($dir['arah_remote_control'])) {
                    $savedDirections[$index] = $report->rcDirections()->create([
                        'arah_remote_control' => $dir['arah_remote_control'],
                        'penyulang' => $dir['penyulang'] ?? '-',
                    ]);
                }
            }
        }

        // RC Commissionings
        if ($request->has('rc_commissionings')) {
            foreach ($request->input('rc_commissionings', []) as $comm) {
                if (!empty($comm['point_id']) || !empty($comm['database_field'])) {
                    $directionIndex = $comm['_dir_id'] ?? null;
                    $directionId = isset($savedDirections[$directionIndex]) ? $savedDirections[$directionIndex]->id : null;

                    $report->rcCommissionings()->create([
                        'rc_direction_id' => $directionId,
                        'database_field' => $comm['database_field'] ?? null,
                        'point_id' => $comm['point_id'] ?? null,
                        'terminal_kubiker' => $comm['terminal_kubiker'] ?? null,
                        'signaling_gh' => isset($comm['signaling_gh']),
                        'signaling_dcc' => isset($comm['signaling_dcc']),
                        'control_dcc_rtu' => isset($comm['control_dcc_rtu']),
                        'control_dcc_rele_plat' => isset($comm['control_dcc_rele_plat']),
                        'control_dcc_lbs' => isset($comm['control_dcc_lbs']),
                        'note' => $comm['note'] ?? null,
                    ]);
                }
            }
        }

        // GFD Inspections
        if ($request->has('gfd_inspections')) {
            foreach ($request->input('gfd_inspections', []) as $insp) {
                if (!empty($insp['item_id'])) {
                    $report->gfdInspections()->create([
                        'item_id' => $insp['item_id'],
                        'ada' => isset($insp['ada']),
                        'tidak_ada' => isset($insp['tidak_ada']),
                        'rusak' => isset($insp['rusak']),
                    ]);
                }
            }
        }

        // Upload images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('report-images', 'public');
                $report->images()->create([
                    'image' => $path,
                    'description' => $request->input("image_descriptions.{$index}"),
                ]);
            }
        }
    }

    private function authorizeView(TaskReport $report): void
    {
        $user = auth()->user();
        if ($user->isPegawai() && $report->user_id !== $user->id) {
            abort(403);
        }
        if ($user->isAdmin() && $report->status === 'draft') {
            abort(403);
        }
        if ($user->isPimpinan() && $report->status_admin !== 'Disetujui') {
            abort(403);
        }
    }

    private function authorizeEdit(TaskReport $report): void
    {
        $user = auth()->user();
        if ($report->user_id !== $user->id || !$report->canBeEdited()) {
            abort(403);
        }
    }
}
