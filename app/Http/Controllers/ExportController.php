<?php

namespace App\Http\Controllers;

use App\Models\TaskReport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportSingleReport(TaskReport $report)
    {
        $filename = 'laporan-' . str_pad($report->id, 5, '0', STR_PAD_LEFT) . '-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new \App\Exports\SingleTaskReportExport($report), $filename);
    }

    public function exportWordPhotoDoc(TaskReport $report)
    {
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $phpWord->setDefaultFontName('Arial');
        $phpWord->setDefaultFontSize(10);

        $section = $phpWord->addSection([
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.5),
            'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.5),
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.5),
            'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.5),
            'headerHeight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.5),
            'footerHeight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(0.5),
        ]);

        // Header Table Style
        $headerTableStyle = [
            'borderColor' => '000000',
            'borderSize'  => 18,
            'cellMargin'  => 50,
            'width'       => 5000, // 100% width (5000 = 100%)
            'unit'        => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT,
            'layout'      => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED,
        ];
        $phpWord->addTableStyle('HeaderTable', $headerTableStyle);
        
        $header = $section->addHeader();
        $table = $header->addTable('HeaderTable');

        // Row 1
        $table->addRow();
        $table->addCell(4000, ['gridSpan' => 3, 'valign' => 'center', 'width' => 4000, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT])
              ->addText('FOTO DOKUMENTASI PEKERJAAN', ['bold' => true, 'italic' => true, 'size' => 16, 'color' => '000000'], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        
        $table->addCell(1000, ['valign' => 'center', 'width' => 1000, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT])
              ->addText('GARDU', ['size' => 10, 'color' => '000000'], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // Row 2
        $table->addRow();
        $table->addCell(1000, ['valign' => 'center', 'width' => 1000, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT])
              ->addText('PEKERJAAN', ['color' => '000000']);
        $table->addCell(3000, ['gridSpan' => 2, 'valign' => 'center', 'width' => 3000, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT])
              ->addText(strtoupper($report->task_type ?? 'PENGADAAN & PEMASANGAN GFD UNTUK PERCEPATAN RECOVERY TIME'), ['bold' => true, 'color' => '000000']);
        $table->addCell(1000, ['vMerge' => 'restart', 'valign' => 'center', 'width' => 1000, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT])
              ->addText($report->gardu, ['bold' => true, 'size' => 20, 'color' => '000000'], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // Row 3
        $table->addRow();
        $table->addCell(1000, ['valign' => 'center', 'width' => 1000, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT])
              ->addText('SPK NO.', ['color' => '000000']);
        $table->addCell(1750, ['valign' => 'center', 'width' => 1750, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT])
              ->addText('AI.0104.SPK/DAN.01.01/F32070000/2024', ['bold' => true, 'color' => '000000']);
        $table->addCell(1250, ['valign' => 'center', 'width' => 1250, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT])
              ->addText('TANGGAL : ' . ($report->date ? strtoupper($report->date->translatedFormat('d F Y')) : '-'), ['color' => '000000']);
        $table->addCell(1000, ['vMerge' => 'continue', 'width' => 1000, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT]);

        // Row 4
        $table->addRow();
        $table->addCell(1000, ['valign' => 'center', 'width' => 1000, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT])
              ->addText('UP3', ['color' => '000000']);
        $table->addCell(3000, ['gridSpan' => 2, 'valign' => 'center', 'width' => 3000, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT])
              ->addText('TELUKNAGA', ['bold' => true, 'color' => '000000']);
        $table->addCell(1000, ['vMerge' => 'continue', 'width' => 1000, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT]);

        // Row 5: TYPE GARDU
        $table->addRow();
        $table->addCell(1000, ['valign' => 'center', 'width' => 1000, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT])
              ->addText('TYPE GARDU', ['color' => '000000']);
        $table->addCell(3000, ['gridSpan' => 2, 'valign' => 'center', 'width' => 3000, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT])
              ->addText(strtoupper($report->type_gardu ?? '-'), ['bold' => true, 'color' => '000000']);
        $table->addCell(1000, ['vMerge' => 'continue', 'width' => 1000, 'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT]);

        $header->addTextBreak(1, ['size' => 4]);

        // Images Table Style
        $photoTableStyle = [
            'borderColor' => '000000',
            'borderTopSize' => 18,
            'borderBottomSize' => 18,
            'borderLeftSize' => 18,
            'borderRightSize' => 18,
            'insideHSize' => 0,
            'insideVSize' => 0,
            'cellMargin'  => 50,
            'width'       => 5000,
            'unit'        => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT,
        ];
        $phpWord->addTableStyle('PhotoTable', $photoTableStyle);

        $images = $report->images;
        $count = count($images);
        $imagesPerPage = 6;
        $pageCount = max(1, ceil($count / $imagesPerPage));

        for ($p = 0; $p < $pageCount; $p++) {
            $photoTable = $section->addTable('PhotoTable');
            
            $start = $p * $imagesPerPage;
            $end = min($start + $imagesPerPage, $count);
            
            for ($i = $start; $i < $end; $i += 2) {
                $photoTable->addRow(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(6));
                
                $cell1 = $photoTable->addCell(5500);
                $path1 = storage_path('app/public/' . $images[$i]->image);
                if (file_exists($path1)) {
                    $cell1->addImage($path1, [
                        'height' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(4),
                        'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
                    ]);
                }

                $cell2 = $photoTable->addCell(5500);
                if ($i + 1 < $end) {
                    $path2 = storage_path('app/public/' . $images[$i + 1]->image);
                    if (file_exists($path2)) {
                        $cell2->addImage($path2, [
                            'height' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(4),
                            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER
                        ]);
                    }
                }
            }
            
            if ($p < $pageCount - 1) {
                $section->addPageBreak();
            }
        }

        // Footer
        $footer = $section->addFooter();
        $footerTable = $footer->addTable([
            'width' => 5000,
            'unit' => \PhpOffice\PhpWord\SimpleType\TblWidth::PERCENT,
            'layout' => \PhpOffice\PhpWord\Style\Table::LAYOUT_FIXED
        ]);
        $footerTable->addRow();
        
        $footerImagePath = public_path('assets/img/Picture4.png');
        if (file_exists($footerImagePath)) {
            $cell1 = $footerTable->addCell(1000, ['valign' => 'center']);
            $cell1->addImage($footerImagePath, [
                'height' => \PhpOffice\PhpWord\Shared\Converter::cmToPixel(1.2),
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START
            ]);
            $cell2 = $footerTable->addCell(4000, ['valign' => 'center']);
            $cell2->addText('PT WAHANA CAHAYA SUKSES', ['bold' => true, 'color' => '000000', 'size' => 10]);
        } else {
            $cell = $footerTable->addCell(5000, ['valign' => 'center']);
            $cell->addText('PT WAHANA CAHAYA SUKSES', ['bold' => true, 'color' => '000000', 'size' => 10]);
        }

        $filename = 'foto-dokumentasi-' . str_pad($report->id, 5, '0', STR_PAD_LEFT) . '-' . now()->format('Y-m-d') . '.docx';
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $tempPath = storage_path('app/public/' . $filename);
        $objWriter->save($tempPath);

        return response()->download($tempPath)->deleteFileAfterSend(true);
    }
}
