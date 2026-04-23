<?php

namespace App\Exports;

use App\Models\TaskReport;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use App\Exports\Sheets\RcGeneralSheet;
use App\Exports\Sheets\RcDenahSheet;
use App\Exports\Sheets\RcCommissioningSheet;
use App\Exports\Sheets\GfdFormEntrySheet;
use App\Exports\Sheets\GfdLayoutSheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SingleTaskReportExport implements WithMultipleSheets, WithEvents
{
    protected $report;

    public function __construct(TaskReport $report)
    {
        $this->report = $report->load(['user', 'images', 'rcDirections', 'rcCommissionings.point', 'rcCommissionings.direction', 'gfdInspections.item']);
    }

    public function sheets(): array
    {
        $sheets = [];

        if ($this->report->isRc()) {
            $sheets[] = new RcGeneralSheet($this->report);
            $sheets[] = new RcDenahSheet();
            $sheets[] = new RcCommissioningSheet($this->report);
        } elseif ($this->report->isGfd()) {
            $sheets[] = new GfdFormEntrySheet($this->report);
            $sheets[] = new GfdLayoutSheet();
        }

        return $sheets;
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function(BeforeWriting $event) {
                if ($this->report->denah_sld_file && file_exists(storage_path('app/public/' . $this->report->denah_sld_file))) {
                    try {
                        $targetSpreadsheet = $event->writer->getDelegate();
                        $path = storage_path('app/public/' . $this->report->denah_sld_file);
                        $sourceSpreadsheet = IOFactory::load($path);

                        $placeholderTitle = $this->report->isRc() ? 'Denah SLD' : 'Layout';
                        $placeholderSheet = $targetSpreadsheet->getSheetByName($placeholderTitle);
                        
                        if ($placeholderSheet) {
                            $index = $targetSpreadsheet->getIndex($placeholderSheet);
                            $targetSpreadsheet->removeSheetByIndex($index);

                            foreach ($sourceSpreadsheet->getAllSheets() as $sourceSheet) {
                                $targetSpreadsheet->addExternalSheet($sourceSheet->copy(), $index++);
                            }
                        }
                    } catch (\Exception $e) {
                        // Fallback: keep placeholders if error occurs during merging
                    }
                }
            },
        ];
    }
}
