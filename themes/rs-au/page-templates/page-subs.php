<?php

/**
 * Template Name: Subs Cron
 */

use Monolog\Logger;
use Logtail\Monolog\LogtailHandler;

$logger = new Logger('subs-cron');
$logger->pushHandler(new LogtailHandler(LOGTAIL_SOURCE_TOKEN));

try {    
   $tbm = new TBMMagSub();

    $subs = $tbm->tbm_mag_sub_renewals();

    $logger->info('Subs cron ran successfully.');
} catch (Exception $e) {
    $logger->error('Something bad happened.', [
        'e' => $e
    ]);
}