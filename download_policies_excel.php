<?php
require 'vendor/autoload.php'; // Load PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['download_excel'])) {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Headers
    $sheet->setCellValue('A1', 'Policy');
    $sheet->setCellValue('B1', 'Applicable');
    $sheet->setCellValue('C1', 'Justification');

    foreach ($_POST['policy_id'] as $index => $id) {
        $row = $index + 2;
        $name = $_POST['policy_name'][$index];
        $applicable = isset($_POST['applicable_status'][$index]) ? ($_POST['applicable_status'][$index] == '1' ? 'Yes' : 'No') : 'Not Selected';
        $justification = $_POST['justification'][$index] ?? '';

        $sheet->setCellValue("A{$row}", $name);
        $sheet->setCellValue("B{$row}", $applicable);
        $sheet->setCellValue("C{$row}", $justification);
    }

    // Set headers to prompt download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Applicable_Policies.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}
