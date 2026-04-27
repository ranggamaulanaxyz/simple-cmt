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
        $drawing->setPath(public_path('assets/img/Picture3.png'));
        $drawing->setHeight(60);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(10);
        $drawing->setOffsetY(5);

        return $drawing;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 1,
            'B' => 6,
            'C' => 30,
            'D' => 2,
            'E' => 10,
            'F' => 4,
            'G' => 4,
            'H' => 2,
            'I' => 10,
            'J' => 2,
            'K' => 3,
            'L' => 2,
            'M' => 15,
            'N' => 1,
            'O' => 3,
            'P' => 1,
            'Q' => 10,
            'R' => 1,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                
                // Page Setup
                $sheet->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setOrientation(PageSetup::ORIENTATION_PORTRAIT);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
                
                $sheet->getPageMargins()->setTop(0.5);
                $sheet->getPageMargins()->setRight(0.5);
                $sheet->getPageMargins()->setLeft(0.5);
                $sheet->getPageMargins()->setBottom(0.5);

                // Outer Border for Metadata Section
                $sheet->getStyle('B6:Q7')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('B8:Q8')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('B9:J9')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('K9:Q9')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('B10:J10')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('K10:Q10')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('B11:Q11')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('B12:Q12')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('B13:Q14')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('H14:Q14')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle('B15:G15')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('H15:Q15')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle('B16:G16')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('H16:Q16')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle('B17:G17')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('H17:Q17')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle('B18:G18')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('H18:Q18')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle('B19:G19')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('H19:Q19')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle('B20:G20')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('H20:Q20')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle('B21:G21')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('H21:Q21')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

                $sheet->getStyle('B22:G22')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);
                $sheet->getStyle('H22:Q22')->getBorders()->getOutline()->setBorderStyle(Border::BORDER_THIN);

                // Dynamic borders for signature area based on inspection items
                $inspectionCount = $this->report->gfdInspections->count();
                $startRow = 25 + (2 * $inspectionCount) + 1;
                $endRow = $startRow + 6;

                foreach (range($startRow, $endRow) as $row) {
                    $sheet->getStyle('B' . $row . ':Q' . $row)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_THIN);
                }
            },
        ];
    }
}
