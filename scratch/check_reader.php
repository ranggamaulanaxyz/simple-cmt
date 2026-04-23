<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$tempFile = 'test.xlsx';
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$spreadsheet->createSheet();
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save($tempFile);

try {
    $reader = IOFactory::createReaderForFile($tempFile);
    echo "Reader type: " . get_class($reader) . "\n";
    if (method_exists($reader, 'listWorksheetNames')) {
        echo "listWorksheetNames exists!\n";
        print_r($reader->listWorksheetNames($tempFile));
    } else {
        echo "listWorksheetNames DOES NOT exist\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

unlink($tempFile);
