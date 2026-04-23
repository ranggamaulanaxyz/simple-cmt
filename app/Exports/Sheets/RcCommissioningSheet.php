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

class RcCommissioningSheet implements FromView, WithTitle, WithStyles, ShouldAutoSize, WithEvents, WithDrawings, WithColumnWidths
{
    protected $report;

    public function __construct(TaskReport $report)
    {
        $this->report = $report;
    }

    public function view(): View
    {
        return view('exports.sheets.rc_commissioning', [
            'report' => $this->report
        ]);
    }

    public function title(): string
    {
        return 'Commissioning';
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
        $drawing1->setOffsetX(10); // Adjust as needed
        $drawing1->setOffsetY(10); // Adjust as needed

        $drawing2 = new Drawing();
        $drawing2->setName('Logo Right');
        $drawing2->setDescription('Logo Right');
        $drawing2->setPath(public_path('assets/img/Picture2.jpg'));
        $drawing2->setHeight(60);
        $drawing2->setCoordinates('P1');
        $drawing2->setOffsetX(10); // Adjust as needed
        $drawing2->setOffsetY(10); // Adjust as needed

        return [$drawing1, $drawing2];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6.5,
            'B' => 9.1,
            'C' => 9.3,
            'D' => 9.1,
            'E' => 8.0,
            'F' => 8.0,
            'G' => 3.0,
            'H' => 3.0,
            'I' => 3.0,
            'J' => 3.0,
            'K' => 3.0,
            'L' => 3.0,
            'M' => 5.0,
            'N' => 5.0,
            'O' => 10.0,
            'P' => 14.0,
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
