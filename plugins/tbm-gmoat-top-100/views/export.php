<?php
require plugin_dir_path(__FILE__) . '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$header = ['Movie Title', '# of votes'];

$spreadsheet = new Spreadsheet();

$sheet1 = $spreadsheet->getActiveSheet();
$sheet1->setTitle('Top Movies');
$sheet1->fromArray([$header], NULL, 'A1');

global $wpdb;

$top_movies_query1 = $wpdb->prepare("
          SELECT
            m.id, m.title, COUNT( me.movie_id ) count_entries
          FROM
            {$wpdb->prefix}gmoat_movies m
              JOIN {$wpdb->prefix}gmoat_movie_entries me
                ON m.id = me.movie_id
          WHERE
            me.movie_id IS NOT NULL
          GROUP BY
            m.id
          ORDER BY
            count_entries DESC
        ");
$top_movies1 = $wpdb->get_results($top_movies_query1);
$movies1 = wp_list_pluck($top_movies1, 'count_entries', 'title');

$top_movies_query2 = $wpdb->prepare("
          SELECT
            me.movie_title title, COUNT( me.movie_title ) count_entries
          FROM
            {$wpdb->prefix}gmoat_movie_entries me
          WHERE
            me.movie_title IS NOT NULL
          GROUP BY
            me.movie_title
          ORDER BY
            count_entries DESC
        ");
$top_movies2 = $wpdb->get_results($top_movies_query2);
$movies2 = wp_list_pluck($top_movies2, 'count_entries', 'title');

$movies = $movies1 + $movies2;

if ($movies) :
    arsort($movies);
    $i = 2;
    foreach ($movies as $title => $count_entries) :
        $line = [$title, $count_entries];
        $sheet1->fromArray([$line], NULL, 'A' . $i);
        $i++;
    endforeach; // For Each $movie in $top_movies 
endif; // If $top_movies


$user_movies_query1 = $wpdb->prepare("
          SELECT
            m.id, m.title, COUNT( e.wildcard_movie_id ) count_entries
          FROM
            {$wpdb->prefix}gmoat_entries e
              JOIN {$wpdb->prefix}gmoat_movies m
                ON m.id = e.wildcard_movie_id
          WHERE
            e.wildcard_movie_id IS NOT NULL
          GROUP BY
            m.title
          ORDER BY
            count_entries DESC
        ");
$top_user_movies1 = $wpdb->get_results($user_movies_query1);
$user_movies1 = wp_list_pluck($top_user_movies1, 'count_entries', 'title');

$user_movies_query2 = $wpdb->prepare("
          SELECT
            e.wildcard_movie_title title, COUNT( e.wildcard_movie_title ) count_entries
          FROM
            {$wpdb->prefix}gmoat_entries e
          WHERE
            e.wildcard_movie_title IS NOT NULL
          GROUP BY
            e.wildcard_movie_title
          ORDER BY
            count_entries DESC
        ");
$top_user_movies2 = $wpdb->get_results($user_movies_query2);
$user_movies2 = wp_list_pluck($top_user_movies2, 'count_entries', 'title');

$user_movies = $user_movies1 + $user_movies2;

$sheet2 = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Wildcard');
$spreadsheet->addSheet($sheet2, 1);
$sheet2->fromArray([$header], NULL, 'A1');

if ($user_movies) :
    arsort($user_movies);
    $i = 2;
    foreach ($user_movies as $title => $count_entries) :
        $line = [$title, $count_entries];
        $sheet2->fromArray([$line], NULL, 'A' . $i);
        $i++;
    endforeach; // For Each $movie in $top_user_movies 
endif; // If $top_user_movies

// $writer = new Xlsx($spreadsheet);
// $writer->save(plugin_dir_path(__FILE__) . '../results/hello world.xlsx');

// redirect output to client browser

$filename = "GMOAT (" . date("d-M-Y") . ")";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
die();