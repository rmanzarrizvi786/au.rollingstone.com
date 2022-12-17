<?php
require plugin_dir_path(__FILE__) . '../../tbm-gmoat-top-100/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

$header = ['First name', 'Last name', 'Email', 'Phone', 'Postcode', 'Current Status', 'Reason', 'Submitted at',];

$spreadsheet = new Spreadsheet();

$spreadsheet->getDefaultStyle()->getAlignment()->setWrapText(true);

$sheet1 = $spreadsheet->getActiveSheet();
$sheet1->setTitle('SCU Rolling Stone Scholarship');
$sheet1->fromArray([$header], NULL, 'A1');

global $wpdb;

$query = ("
          SELECT
            *
          FROM
            {$wpdb->prefix}scu_scholarship_{$year}
        ");
$entries = $wpdb->get_results($query);

if ($entries) :
  $i = 2;
  foreach ($entries as $entry) :
    $line = [$entry->firstname, $entry->lastname, $entry->email, $entry->phone, $entry->postcode, $entry->current_status, stripslashes($entry->reason), $entry->created_at];
    $sheet1->fromArray([$line], NULL, 'A' . $i);
    $i++;
  endforeach; // For Each $movie in $top_movies 
endif; // If $top_movies

$filename = "SCU Rolling Stone Scholarship (" . date("d-M-Y") . ")";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
die();
