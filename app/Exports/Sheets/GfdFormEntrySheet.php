<?php

namespace App\Exports\Sheets;

use App\Models\TaskReport;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class GfdFormEntrySheet implements FromView, WithTitle, WithStyles, WithColumnWidths, WithDrawings, WithEvents
{
    protected $report;

    public function __construct(TaskReport $report)
    {
        $this->report = $report;
    }

    public function view(): View
    {
        return view('exports.sheets.gfd_form_entry', [
            'report' => $this->report
        ]);
    }

    public function title(): string
    {
        return 'Form Entry';
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('PLN Logo');
        $drawing->setPath(public_path('logo.png'));
        $drawing->setHeight(40);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);

        return $drawing;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 4,  // Margin/Logo
            'B' => 12, // Information label
            'C' => 3,  // Colon
            'D' => 10, // Value
            'E' => 10, // Value
            'F' => 10, // Value
            'G' => 10, // Value
            'H' => 10, // Value
            'I' => 12, // Sub-label
            'J' => 3,  // Colon
            'K' => 8,  // Box
            'L' => 8,  // Tag
            'M' => 8,  // Box
            'N' => 8,  // Tag
            'O' => 8,  // Box
            'P' => 8,  // Tag
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // General font and alignment
        $sheet->getStyle('A:P')->getFont()->setName('Arial');
        $sheet->getStyle('A:P')->getFont()->setSize(9);
        $sheet->getStyle('A:P')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        
        // Wrap text for address area
        $sheet->getStyle('D11')->getAlignment()->setWrapText(true);

        return [
            // Row styling
            4 => ['font' => ['bold' => true, 'size' => 12]],
            // Data borders are handled via HTML in Blade mostly, 
            // but we can add global font bolding for headers here if needed.
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
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
