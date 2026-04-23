<?php

namespace App\Exports;

use App\Models\TaskReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class TaskReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $type;
    protected $status;
    protected $statusAdmin;
    protected $statusPimpinan;

    public function __construct($type = null, $status = null, $statusAdmin = null, $statusPimpinan = null)
    {
        $this->type = $type;
        $this->status = $status;
        $this->statusAdmin = $statusAdmin;
        $this->statusPimpinan = $statusPimpinan;
    }

    public function collection(): Collection
    {
        $query = TaskReport::with(['user', 'images']);

        if ($this->type) {
            $query->byType($this->type);
        }
        if ($this->status) {
            $query->byStatus($this->status);
        }
        if ($this->statusAdmin) {
            $query->whereIn('status_admin', (array) $this->statusAdmin);
        }
        if ($this->statusPimpinan) {
            $query->whereIn('status_pimpinan', (array) $this->statusPimpinan);
        }

        // Base filtering for export (exclude drafts unless explicitly requested)
        if (!$this->status && !$this->statusAdmin && !$this->statusPimpinan) {
            $query->whereIn('status', ['terverifikasi', 'direview']);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tipe',
            'Tanggal',
            'Status',
            'Status Admin',
            'Status Pimpinan',
            'Pegawai',
            'Gardu',
            'Alamat',
            'Latitude',
            'Longitude',
            'Arah',
            'Catatan',
            'RTU',
            'Modem',
            'Task Type (GFD)',
            'Penyulang',
            'Gardu Induk',
        ];
    }

    public function map($report): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $report->type_label,
            $report->date->format('d/m/Y'),
            $report->status_label,
            $report->status_admin,
            $report->status_pimpinan,
            $report->user->name,
            $report->gardu,
            $report->address,
            $report->latitude,
            $report->longitude,
            $report->arah,
            $report->notes,
            $report->rtu,
            $report->modem,
            $report->task_type,
            $report->penyulang,
            $report->gardu_induk,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
