<?php

namespace App\Exports\Sheets;

use App\Models\TaskReport;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RcFotoSheet implements FromView, WithTitle, WithStyles, ShouldAutoSize, WithEvents, WithDrawings, WithColumnWidths
{
    protected $report;

    public function __construct(TaskReport $report)
    {
        $this->report = $report;
    }

    public function view(): View
    {
        return view('exports.sheets.rc_foto', [
            'report' => $this->report
        ]);
    }

    public function title(): string
    {
        return 'Foto';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            'A1' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
            'P1' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function drawings()
    {
        $drawings = [];

        // Header Logos
        $drawing1 = new Drawing();
        $drawing1->setName('Logo Left');
        $drawing1->setDescription('Logo Left');
        $drawing1->setPath(public_path('assets/img/Picture1.png'));
        $drawing1->setHeight(60);
        $drawing1->setCoordinates('A1');
        $drawing1->setOffsetX(10);
        $drawing1->setOffsetY(10);
        $drawings[] = $drawing1;

        $drawing2 = new Drawing();
        $drawing2->setName('Logo Right');
        $drawing2->setDescription('Logo Right');
        $drawing2->setPath(public_path('assets/img/Picture2.jpg'));
        $drawing2->setHeight(60);
        $drawing2->setCoordinates('P1');
        $drawing2->setOffsetX(10);
        $drawing2->setOffsetY(10);
        $drawings[] = $drawing2;

        // Report Photos
        $images = $this->report->images;
        $row = 11;
        $colLeft = 'A';
        $colRight = 'I';

        foreach ($images->chunk(2) as $chunk) {
            $chunk = $chunk->values();
            foreach ($chunk as $index => $image) {
                if (file_exists(storage_path('app/public/' . $image->image))) {
                    $col = ($index === 0) ? $colLeft : $colRight;
                    $drawing = new Drawing();
                    $drawing->setName($image->description ?? 'Foto');
                    $drawing->setDescription($image->description ?? 'Foto');
                    $drawing->setPath(storage_path('app/public/' . $image->image));
                    $drawing->setHeight(220); // Slightly smaller to accommodate text
                    $drawing->setCoordinates($col . $row);
                    $drawing->setOffsetX(140);
                    $drawing->setOffsetY(40); // Offset down to make room for description text
                    $drawings[] = $drawing;
                }
            }
            $row += 17;
        }

        return $drawings;
    }

    public function columnWidths(): array
    {
        // Increased by 60% (original 4 -> 6.4, 6 -> 9.6)
        return [
            'A' => 9.6,
            'B' => 9.6,
            'C' => 9.6,
            'D' => 9.6,
            'E' => 9.6,
            'F' => 9.6,
            'G' => 9.6,
            'H' => 2.0, // Reduced H
            'I' => 9.6,
            'J' => 9.6,
            'K' => 9.6,
            'L' => 9.6,
            'M' => 9.6,
            'N' => 9.6,
            'O' => 9.6,
            'P' => 9.6,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Row Heights for header and spacers
                $sheet->getRowDimension(7)->setRowHeight(10);
                $sheet->getRowDimension(8)->setRowHeight(10);
                $sheet->getRowDimension(9)->setRowHeight(10);

                $images = $this->report->images;
                $row = 11;

                foreach ($images->chunk(2) as $chunk) {
                    $chunk = $chunk->values();
                    // Title row height
                    $sheet->getRowDimension($row)->setRowHeight(25);

                    // Image areas (15 rows)
                    for ($i = $row + 1; $i <= $row + 15; $i++) {
                        $sheet->getRowDimension($i)->setRowHeight(20);
                    }

                    // Spacer row
                    $sheet->getRowDimension($row + 16)->setRowHeight(15);

                    // Styling for photo boxes (Thin borders)
                    // Box 1: A to G
                    $sheet->getStyle('A' . $row . ':G' . ($row + 15))->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                    // Set Description 1
                    $sheet->setCellValue('A' . $row, $chunk[0]->description ?? '');
                    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('A' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

                    // Merging
                    $sheet->mergeCells('A' . $row . ':G' . ($row + 15));
    
                    if ($chunk->count() > 1) {
                        // Box 2: I to P
                        $sheet->getStyle('I' . $row . ':P' . ($row + 15))->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                        // Set Description 2
                        $sheet->setCellValue('I' . $row, $chunk[1]->description ?? '');
                        $sheet->getStyle('I' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $sheet->getStyle('I' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

                        // Merging
                        $sheet->mergeCells('I' . $row . ':P' . ($row + 15));
                    }

                    $row += 17;
                }

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
