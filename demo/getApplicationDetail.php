<?php

require_once(__DIR__ . '/../vendor/autoload.php');
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use MallPayLib\MallPayClient;

require "config.php";

$logger = new Logger('MallPayTestApp');
$logger->pushHandler(new StreamHandler(__DIR__ . '/app.log', Logger::DEBUG));

session_start();
?>



<html><body>
<form>
applicationId: <input name="applicationId" type="text" value="<?php echo isset($_SESSION['applicationId']) ? $_SESSION['applicationId'] : "" ?>"/>
    <br/>
    <input type="submit" value="getApplicationDetail">
</form>
<p/><a href="index.php">back</a>
<hr/>



<?php
if (isset($_REQUEST["applicationId"])) {
    $applicationId = $_REQUEST['applicationId'];

    try {
        echo "calling getApplicationDetail($applicationId)<p/>";

        $mallPay = new MallPayClient($mallPayUser, $mallPayPass, $mallPayUrl, $logger);
        $mallPay->login();

        $responseData = $mallPay->getApplicationDetail($applicationId);

        echo "<hr/>";
        echo "Result summary<p/>";
        echo "applicationId = $applicationId<br/>";
        echo "applicationState = ".$responseData['state']."<br/>";
        echo "applicationStateReason = ".$responseData['stateReason']."<br/>";

        echo "<hr/>";
        echo "result:<p/><pre>";
        print_r($responseData);
        echo "</pre>";

        $_SESSION['applicationId'] = $applicationId;
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
