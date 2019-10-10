<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('MallPayTestApp');
$logger->pushHandler(new StreamHandler(__DIR__ . '/app.log', Logger::DEBUG));

$thisUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$logger->info("Backend notification from MallPay gateway. URL = $thisUrl");
