<?php
require 'vendor/autoload.php';
require 'includes/connection.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Create Spreadsheet
$spreadsheet = new Spreadsheet();
$spreadsheet->removeSheetByIndex(0); // Remove default sheet
$sheet = $spreadsheet->createSheet();
$spreadsheet->setActiveSheetIndex(0);
$sheet->setTitle('Summary');

// Fetch latest SOA entries only
$query = "
SELECT s1.*
FROM soa s1
INNER JOIN (
    SELECT soa_policy_type, soa_policy_id, MAX(soa_created_at) AS max_time
    FROM soa
    GROUP BY soa_policy_type, soa_policy_id
) s2 ON s1.soa_policy_type = s2.soa_policy_type 
     AND s1.soa_policy_id = s2.soa_policy_id 
     AND s1.soa_created_at = s2.max_time
ORDER BY s1.soa_id ASC
";

$result = $connection->query($query);
$soa_data = [];
$applicableCount = 0;
$notApplicableCount = 0;

while ($row = $result->fetch_assoc()) {
    $applicabilityText = $row['soa_applicable'] === 'Y' ? 'Applicable' : ($row['soa_applicable'] === 'N' ? 'Not Applicable' : '');

    $soa_data[] = [
        'name' => $row['soa_policy_name'],
        'applicable' => $applicabilityText,
        'ra' => $row['soa_ra'] ? 'Y' : '',
        'br_bp' => $row['soa_br_bp'] ? 'Y' : '',
        'lr_co' => $row['soa_lr_co'] ? 'Y' : '',
        'justification' => $row['soa_justification'] ?? '',
        'applicable_reason' => $row['soa_applicable_reason'] ?? ''
    ];

    if ($row['soa_applicable'] === 'Y') $applicableCount++;
    if ($row['soa_applicable'] === 'N') $notApplicableCount++;
}

// Header rows
$sheet->setCellValue('A1', 'Applicable Controls');
$sheet->setCellValue('C1', $applicableCount);
$sheet->mergeCells('D1:H2');
$sheet->setCellValue('D1', 'BR/BP - Business Requirement/Best Practices     |     LR/CO - Legal Requirement/Contractual Obligation     |     RA - Risk Assessment');

// Style for merged D1:H2
$sheet->getStyle('D1:H2')->applyFromArray([
    'alignment' => [
        'wrapText' => true,
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ]
]);


$sheet->setCellValue('A2', 'Not-Applicable Controls');
$sheet->setCellValue('C2', $notApplicableCount);

// Table Headers
$sheet->mergeCells('A3:B3');
$sheet->setCellValue('A3', 'Policy Name');
$sheet->setCellValue('C3', 'Applicable');
$sheet->setCellValue('D3', 'RA');
$sheet->setCellValue('E3', 'BR/BP');
$sheet->setCellValue('F3', 'LR/CO');
$sheet->setCellValue('G3', 'Rationale for Applicability');
$sheet->setCellValue('H3', 'Justification for Non-Applicability');
$sheet->getStyle('A3:H3')->getFont()->setBold(true);

// Content Rows
$rowIndex = 4;
foreach ($soa_data as $data) {
    $sheet->mergeCells("A{$rowIndex}:B{$rowIndex}");
    $sheet->setCellValue("A{$rowIndex}", $data['name']);
    $sheet->setCellValue("C{$rowIndex}", $data['applicable']);
    $sheet->setCellValue("D{$rowIndex}", $data['ra']);
    $sheet->setCellValue("E{$rowIndex}", $data['br_bp']);
    $sheet->setCellValue("F{$rowIndex}", $data['lr_co']);
    $sheet->setCellValue("G{$rowIndex}", $data['applicable'] === 'Applicable' ? $data['applicable_reason'] : '');
    $sheet->setCellValue("H{$rowIndex}", $data['applicable'] === 'Not Applicable' ? $data['justification'] : '');
    $rowIndex++;
}

// Column Widths
$sheet->getColumnDimension('A')->setWidth(85); // ✅ Set column A width to 85
foreach (range('C', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Styling rows and borders
$sheet->getStyle("A3:H" . ($rowIndex - 1))->applyFromArray([
    'alignment' => [
        'wrapText' => true,
        'vertical' => Alignment::VERTICAL_TOP
    ],
    'borders' => [
        'allBorders' => ['borderStyle' => Border::BORDER_THIN]
    ]
]);

// Green Background for Row 1 and Row 2
$sheet->getStyle('A1:H1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('A9D08E');
$sheet->getStyle('A2:H2')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('A9D08E');

// Bold for rows 1 & 2
$sheet->getStyle('A1:H2')->getFont()->setBold(true);

// Output
// $filename = 'In3Corp_SOA_Summary_' . date('Ymd_His') . '.xlsx';
$filename = 'In3Corp_SOA.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;
