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
    <input name="call" type="submit" value="createApplication">
</form>
<p/><a href="index.php">back</a>
<hr/>

<?php
if (isset($_REQUEST["call"])) {
    try {
        echo "calling createApplication()<p/>";


        //// application/json
        $orderNumber  = time();  // sample order number

        $thisUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $approvedUrl = dirname($thisUrl)."/reply.php?type=approved&orderNumber=".$orderNumber;
        $rejectedUrl = dirname($thisUrl)."/reply.php?type=rejected&orderNumber=".$orderNumber;
        $notifyUrl = dirname($thisUrl)."/notify.php?orderNumber=".$orderNumber;


        $requestData = [
            'customer' => [
                'fullName' => 'Platiti Tester',
                'email' => 'platiti.tester@country.com',
                'phone' => '+420123456789',
            ],
            'order' => [
                'number' => $orderNumber,
                'variableSymbols' => [
                    '1111',
                ],
                'totalPrice' => [
                    'amount' => '12690',
                    'currency' => 'CZK',
                ],
                'totalVat' => [
                    [
                        'amount' => '12690',
                        'currency' => 'CZK',
                        'vatRate' => '15',
                    ]
                ],
                'addresses' => [
                    [
                        'name'=> 'John Doe',
                        'country'=> 'CZ',
                        'city'=> 'Prague',
                        'streetAddress'=> 'Letenská',
                        'streetNumber'=> '22',
                        'zip'=> '140 00',
                        'addressType'=> 'BILLING'
                    ]
                ],
                'items' => [
                    [
                        'code' => 'EXC4677-1a',
                        'name' => 'iPhone 6s 32GB SpaceGray',
                        'totalPrice' => [
                            'amount' => '12590',
                            'currency' => 'CZK',
                        ],
                        'totalVat' => [
                            'amount' => '12590',
                            'currency' => 'CZK',
                            'vatRate' => '15',
                        ],
                    ],
                    [
                        'code' => 'EXC4677-1ac',
                        'name' => 'iPhone 6s case',
                        'totalPrice' => [
                            'amount' => '100',
                            'currency' => 'CZK',
                        ],
                        'totalVat' => [
                            'amount' => '100',
                            'currency' => 'CZK',
                            'vatRate' => '15',
                        ],
                    ],
                ],
            ],
            'type' => 'DEFERRED_PAYMENT',
            'merchantUrls'=> [
                'approvedRedirect'=> $approvedUrl,
                'rejectedRedirect'=> $rejectedUrl,
                'notificationEndpoint'=> $notifyUrl,
            ]
        ];




        echo "requestData: <p/>";
        echo "<pre>".print_r($requestData, true)."</pre><hr>";
        
        $mallPay = new MallPayClient($mallPayUser, $mallPayPass, $mallPayUrl, $logger);
        $mallPay->login();

        $responseData = $mallPay->createApplication($requestData);
        $applicationId = $responseData['id'];

        echo "<hr/>";
        echo "Result summary<p/>";
        echo "applicationId = $applicationId<br/>";
        echo "applicationState = ".$responseData['state']."<br/>";
        echo "applicationStateReason = ".$responseData['stateReason']."<br/>";
        echo "gatewayRedirectUrl = <a href='" . $responseData["gatewayRedirectUrl"] . "'>" . $responseData["gatewayRedirectUrl"] . "</a><br/>";

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
