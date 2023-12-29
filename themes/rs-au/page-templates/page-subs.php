<?php

/**
 * Template Name: Subs Cron
 *
 * @package pmc-rollingstone-2018
 * @since 2018-06-28
 */

use Monolog\Logger;
use Logtail\Monolog\LogtailHandler;

$logger = new Logger("example-app");
$logger->pushHandler(new LogtailHandler(LOGTAIL_SOURCE_TOKEN));

$logger->info("Log message with structured logging.", [
    "item" => "Orange Soda",
    "price" => 100,
]);