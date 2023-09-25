<?php
require plugin_dir_path(__FILE__) . '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$header = ['Movie Title', '# of votes'];

$spreadsheet = new Spreadsheet();

$sheet1 = $spreadsheet->getActiveSheet();
$sheet1->setTitle('Top Movies');
$sheet1->fromArray([$header], NULL, 'A1');

global $wpdb;

$top_movies_query1 = "
          SELECT
            m.id, m.title, COUNT( me.movie_id ) count_entries
          FROM
            {$wpdb->prefix}gmoat_movies_2021 m
              JOIN {$wpdb->prefix}gmoat_movie_entries_2021 me
                ON m.id = me.movie_id
          WHERE
            me.movie_id IS NOT NULL
          GROUP BY
            m.id
          ORDER BY
            count_entries DESC
        ";
$top_movies1 = $wpdb->get_results($top_movies_query1);
$movies1 = wp_list_pluck($top_movies1, 'count_entries', 'title');

$top_movies_query2 = "
          SELECT
            me.movie_title title, COUNT( me.movie_title ) count_entries
          FROM
            {$wpdb->prefix}gmoat_movie_entries_2021 me
          WHERE
            me.movie_title IS NOT NULL
          GROUP BY
            me.movie_title
          ORDER BY
            count_entries DESC
        ";
$top_movies2 = $wpdb->get_results($top_movies_query2);
$movies2 = wp_list_pluck($top_movies2, 'count_entries', 'title');

$movies = [];
foreach ($movies1 as $title => $count_entries) {
  if (isset($movies[$title])) {
    $movies[$title] += $count_entries;
  } else {
    $movies[$title] = $count_entries;
  }
}
foreach ($movies2 as $title => $count_entries) {
  if (isset($movies[$title])) {
    $movies[$title] += $count_entries;
  } else {
    $movies[$title] = $count_entries;
  }
}
// $movies = $movies1 + $movies2;

if ($movies) :
  arsort($movies);
  $i = 2;
  foreach ($movies as $title => $count_entries) :
    $line = [$title, $count_entries];
    $sheet1->fromArray([$line], NULL, 'A' . $i);
    $i++;
  endforeach; // For Each $movie in $top_movies 
endif; // If $top_movies



$header = ['Name', 'Email', 'Reason'];
$entries = $wpdb->get_results("
          SELECT
            *
          FROM
            {$wpdb->prefix}gmoat_entries_2021 e
          WHERE
            e.reason IS NOT NULL AND
            e.reason != ''
        ");

$sheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Comp Entries');
$spreadsheet->addSheet($sheet2, 1);
$sheet2->fromArray([$header], NULL, 'A1');

$sheet2->getStyle('C1:C1000')->getAlignment()->setWrapText(true);

if ($entries) :
  $entries = stripslashes_deep($entries);
  $i = 2;
  foreach ($entries as $entry) :
    $line = [$entry->user_fullname, $entry->user_email, $entry->reason];
    $sheet2->fromArray([$line], NULL, 'A' . $i);
    $i++;
  endforeach; // For Each $movie in $top_user_movies 
endif; // If $top_user_movies

// $writer = new Xlsx($spreadsheet);
// $writer->save(plugin_dir_path(__FILE__) . '../results/hello world.xlsx');

// redirect output to client browser

$filename = "25 Greatest Movies of 2021 (" . date("d-M-Y") . ")";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
die();
