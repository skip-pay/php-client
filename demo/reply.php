<?php

require_once(__DIR__ . '/../vendor/autoload.php');

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
$logger = new Logger('MallPayTestApp');
$logger->pushHandler(new StreamHandler(__DIR__ . '/app.log', Logger::DEBUG));

$thisUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$logger->info("Frontend redirect from MallPay gateway. URL = $thisUrl");

echo "<html><body>";
echo "Reply from MallPay gateway<p/>";
echo "Result: ".$_REQUEST['type']."<p/>";

echo "URL: $thisUrl<p/>";
echo 'Now you should call <a href="getApplicationDetail.php">getApplicationDetail</a><p/>';
echo '<p/><a href="index.php">back</a>';
echo "<html><body>";


