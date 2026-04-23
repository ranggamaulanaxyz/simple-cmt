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

class RcDenahSheet implements FromView, WithTitle, WithStyles, ShouldAutoSize, WithEvents, WithDrawings, WithColumnWidths
{
    protected $report;

    public function __construct(TaskReport $report)
    {
        $this->report = $report;
    }

    public function view(): View
    {
        return view('exports.sheets.rc_denah', [
            'report' => $this->report
        ]);
    }

    public function title(): string
    {
        return 'Denah';
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
        $drawing1 = new Drawing();
        $drawing1->setName('Logo Left');
        $drawing1->setDescription('Logo Left');
        $drawing1->setPath(public_path('assets/img/Picture1.png'));
        $drawing1->setHeight(60);
        $drawing1->setCoordinates('A1');
        $drawing1->setOffsetX(10);
        $drawing1->setOffsetY(10);

        $drawing2 = new Drawing();
        $drawing2->setName('Logo Right');
        $drawing2->setDescription('Logo Right');
        $drawing2->setPath(public_path('assets/img/Picture2.jpg'));
        $drawing2->setHeight(60);
        $drawing2->setCoordinates('K1');
        $drawing2->setOffsetX(10);
        $drawing2->setOffsetY(10);

        return [$drawing1, $drawing2];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8.65,
            'B' => 8.65,
            'C' => 8.65,
            'D' => 8.65,
            'E' => 8.65,
            'F' => 8.65,
            'G' => 8.65,
            'H' => 8.65,
            'I' => 8.65,
            'J' => 15.05,
            'K' => 10.9,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
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
