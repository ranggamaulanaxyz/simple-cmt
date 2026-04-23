<?php

namespace App\Http\Controllers;

use App\Exports\TaskReportExport;
use App\Models\TaskReport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportReports(Request $request)
    {
        $type = $request->input('type');
        $status = $request->input('status');
        $statusAdmin = $request->input('status_admin');
        $statusPimpinan = $request->input('status_pimpinan');
        $filename = 'laporan-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new TaskReportExport($type, $status, $statusAdmin, $statusPimpinan), $filename);
    }

    public function exportSingleReport(TaskReport $report)
    {
        $filename = 'laporan-' . str_pad($report->id, 5, '0', STR_PAD_LEFT) . '-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new \App\Exports\SingleTaskReportExport($report), $filename);
    }
}
