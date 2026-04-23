<?php

namespace App\Exports\Sheets;

use App\Models\TaskReport;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RcGeneralSheet implements FromView, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $report;

    public function __construct(TaskReport $report)
    {
        $this->report = $report;
    }

    public function view(): View
    {
        return view('exports.sheets.rc_general', [
            'report' => $this->report
        ]);
    }

    public function title(): string
    {
        return 'FP.1.01';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 3.2,  // Block 1 starts
            'B' => 15.8,
            'C' => 6.3,
            'D' => 8.4,  // Block 1 total: ~33.7
            'E' => 8.4,  // Block 2 starts
            'F' => 8.4,
            'G' => 8.4,
            'H' => 8.4,  // Block 2 total: 33.6
            'I' => 16.8, // Block 3 starts
            'J' => 16.8, // Block 3 total: 33.6
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->getStyle('A1:B3')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM);
        $sheet->getStyle('C1:J3')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM);
        $sheet->getStyle('A4:J8')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM);

        $num = max($this->report->rcDirections->count(), 16);
        $bodyEndRow = 12 + $num;
        $catatanStartRow = $bodyEndRow + 1;
        $catatanEndRow = $catatanStartRow + 4; // Catatan box is 4 rows
        $signStartRow = $catatanEndRow + 1; // Spacing row in between
        $signEndRow = $signStartRow + 7; // Signatures block is 8 rows total

        // Dynamic Body Outline
        $sheet->getStyle('A9:J' . $bodyEndRow)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM);

        // Dynamic Catatan Outline
        $sheet->getStyle('A' . $catatanStartRow . ':J' . $catatanEndRow)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM);

        // Dynamic Sign Outline
        $sheet->getStyle('A' . $signStartRow . ':J' . $signEndRow)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM);

        // signature Dividers & Space
        $sheet->getStyle('D' . $signStartRow . ':D' . $signEndRow)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);
        $sheet->getStyle('H' . $signStartRow . ':H' . $signEndRow)->getBorders()->getRight()->setBorderStyle(Border::BORDER_MEDIUM);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Set Row Heights
                $sheet->getRowDimension(4)->setRowHeight(10);
                $sheet->getRowDimension(7)->setRowHeight(30);
                $sheet->getRowDimension(8)->setRowHeight(10);
                $sheet->getRowDimension(9)->setRowHeight(30);
                $sheet->getRowDimension(10)->setRowHeight(30);
                $sheet->getRowDimension(11)->setRowHeight(30);
                $sheet->getRowDimension(12)->setRowHeight(30);

                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);

                $sheet->getPageMargins()->setTop(0.5);
                $sheet->getPageMargins()->setRight(0.5);
                $sheet->getPageMargins()->setLeft(0.5);
                $sheet->getPageMargins()->setBottom(0.5);
            },
        ];
    }
}
