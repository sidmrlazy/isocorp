<?php
require 'vendor/autoload.php';
include 'includes/connection.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Headers
$sheet->setCellValue('A1', 'Policy');
$sheet->setCellValue('B1', 'Applicable');
$sheet->setCellValue('C1', 'Justification');

// Fetch latest SOA entries
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
    ORDER BY s1.soa_policy_name
";

$result = mysqli_query($connection, $query);

$rowNum = 2;
while ($row = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue("A{$rowNum}", $row['soa_policy_name']);
    $sheet->setCellValue("B{$rowNum}", is_null($row['soa_applicable']) ? 'Not Selected' : ($row['soa_applicable'] ? 'Yes' : 'No'));
    $sheet->setCellValueExplicit("C{$rowNum}", $row['soa_justification'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
    $rowNum++;
}

// Output Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Applicable_Policies.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
