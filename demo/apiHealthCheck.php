<?php

require_once(__DIR__ . '/../vendor/autoload.php');
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use MallPayLib\MallPayClient;

require "config.php";

$logger = new Logger('MallPayTestApp');
$logger->pushHandler(new StreamHandler(__DIR__ . '/app.log', Logger::DEBUG));

?>



<html><body>
<form>
    <input name="call" type="submit" value="apiHealthCheck">
</form>
<p/><a href="index.php">back</a>
<hr/>



<?php
if (isset($_REQUEST["call"])) {
    try {
        echo "calling apiHealthCheck()<p/>";

        $mallPay = new MallPayClient($mallPayUser, $mallPayPass, $mallPayUrl, $logger);
        $responseData = $mallPay->apiHealthCheck();

        echo "result:<p/><pre>";
        print_r($responseData);
        echo "</pre>";
    } catch (Exception $e) {
        echo "exception: " . get_class($e) . "<p/><pre>";
        echo "status: " . $e->getResponse()->getStatusCode() . "<p/>";
        echo "<pre>";
        print_r($e->getResponse()->getBody()->getContents());
        echo "</pre>";
    }
}
?>

</body></html>
