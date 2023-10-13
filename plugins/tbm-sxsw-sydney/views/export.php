<?php

require plugin_dir_path(__FILE__) . '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;

global $wpdb;

$table = $wpdb->prefix . 'sxsw_sydney_entries_2023';
$entries = $wpdb->get_results("SELECT * FROM {$table}");

$header = ['Name', 'Email', 'Post Code', 'Day 1', 'Day 2', 'Day 3', 'Day 4', 'Created'];
$spreadsheet = new Spreadsheet();

$sheet1 = $spreadsheet->getActiveSheet();
$sheet1->setTitle( 'SXSW Sydney 2023 entries' );
$sheet1->fromArray( [ $header ], NULL, 'A1' );


if ($entries) {
    $entries = stripslashes_deep($entries);
    $i = 2;
    
    foreach ( $entries as $entry ) :
        $line = [ $entry->name, $entry->email, $entry->postcode, $entry->day1, $entry->day2, $entry->day3, $entry->day4, $entry->created ];
        $sheet1->fromArray( [ $line ], NULL, 'A' . $i );
        $i++;
    endforeach;
}





// $entries = $wpdb->get_results("
//           SELECT
//             *
//           FROM
//             {$wpdb->prefix}gmoat_entries_2021 e
//           WHERE
//             e.reason IS NOT NULL AND
//             e.reason != ''
//         ");

// $sheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Comp Entries');

// $spreadsheet->addSheet($sheet2, 1);
// $sheet2->fromArray([$header], NULL, 'A1');

// $sheet2->getStyle('C1:C1000')->getAlignment()->setWrapText(true);

// if ($entries) :
//   $entries = stripslashes_deep($entries);
//   $i = 2;
//   foreach ($entries as $entry) :
//     $line = [$entry->user_fullname, $entry->user_email, $entry->reason];
//     $sheet2->fromArray([$line], NULL, 'A' . $i);
//     $i++;
//   endforeach; // For Each $movie in $top_user_movies 
// endif; // If $top_user_movies

$filename = "SXSW Sydney 2023 (" . date("d-M-Y") . ")";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter( $spreadsheet, 'Xlsx' );
$writer->save('php://output');
wp_die();
