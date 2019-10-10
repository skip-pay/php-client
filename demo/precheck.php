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
    <input name="call" type="submit" value="precheck">
</form>
<p/><a href="index.php">back</a>
<hr/>



<?php
if (isset($_REQUEST["call"])) {
    try {
        echo "calling precheck()<p/>";


//// application/json
        $orderNumber  = time();  // sample order number

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
        ];

        echo "requestData: <p/>";
        echo "<pre>".print_r($requestData, true)."</pre><hr>";

        $mallPay = new MallPayClient($mallPayUser, $mallPayPass, $mallPayUrl, $logger);
        $mallPay->login();

        $responseData = $mallPay->precheck($requestData);

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
