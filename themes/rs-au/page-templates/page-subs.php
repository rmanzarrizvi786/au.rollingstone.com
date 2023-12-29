<?php

/**
 * Template Name: Subs Cron
 */

use Monolog\Logger;
use Logtail\Monolog\LogtailHandler;

$logger = new Logger('subs-cron');
$logger->pushHandler(new LogtailHandler(LOGTAIL_SOURCE_TOKEN));

try {    
    // $logger->info("Log message with structured logging.", [
    //     "item" => "Orange Soda",
    //     "price" => 100,
    // ]);

    throw new Exception('Division by zero.');
} catch (Exception $e) {
    $logger->error("Something bad happened.", [
        "exception" => $e
    ]);
}