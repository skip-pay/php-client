<?php

namespace MallPayLib;

use GuzzleHttp\Client;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use GuzzleHttp\HandlerStack;

/**
 * Class MallPayClient
 * Implements MALLPay API methods.
 * For the MALLPay API documentation see https://mallpayapi.docs.apiary.io
 * Most of the method requires the request data in the php associative array which is converted to json for API call.
 * The response data are converted from json to php associative array.
 * @package MallPayLib
 */
class MallPayClient
{
    private $apiUsername;
    private $apiPassword;
    private $accessToken;
    private $client;
    private $logger;

    /**
     * MallPayClient constructor.
     * @param string $apiUsername
     * @param string $apiPassword
     * @param string $apiUrl
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct($apiUsername, $apiPassword, $apiUrl, $logger)
    {
        $this->apiUsername = $apiUsername;
        $this->apiPassword = $apiPassword;
        $this->logger = $logger;

        $stack = HandlerStack::create();
        if ($logger != null) {
            $stack->push(
                Middleware::log(
                    $logger,
                    new MessageFormatter(get_class($this) . ' {method} {url} {req_body}  -->>  {code} {res_body} {curl_error}')
                )
            );
        }
        $this->client = new Client(['base_uri'=>$apiUrl, 'handler' => $stack,/*, 'verify' => false*/ /*, 'proxy' => 'tcp://localhost:8888'*/]);
    }

    /**
     * Log in using the credentials from constructor
     */
    public function login()
    {
        $data = array('username' => $this->apiUsername, 'password' => $this->apiPassword);
        $response = $this->callApi('POST', '/authentication/v1/partner', $data);
        $this->accessToken = $response['accessToken'];
    }

    /**
     * @param array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createApplication($data)
    {
        return $this->callApi('POST', '/financing/v1/applications', $data);
    }

    /**
     * @param string $applicationId
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getApplicationDetail($applicationId)
    {
        return $this->callApi('GET', '/financing/v1/applications/' . $applicationId);
    }

    /**
     * @param string $applicationId
     * @param null|array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function cancelApplication($applicationId, $data)
    {
        return $this->callApi('PUT', '/financing/v1/applications/' . $applicationId . '/cancel', $data);
    }

    /**
     * @param string $applicationId
     * @param array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function changeApplicationOrder($applicationId, $data)
    {
        return $this->callApi('PUT', '/financing/v1/applications/' . $applicationId . '/order', $data);
    }

    /**
     * @param string $applicationId
     * @param null|array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function markOrderItemsAsCancelled($applicationId, $data)
    {
        return $this->callApi('PUT', '/financing/v1/applications/' . $applicationId . '/order/cancel', $data);
    }

    /**
     * @param string $applicationId
     * @param null|array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function markOrderItemsAsSent($applicationId, $data)
    {
        return $this->callApi('PUT', '/financing/v1/applications/' . $applicationId . '/order/send', $data);
    }

    /**
     * @param string $applicationId
     * @param null|array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function markOrderItemsAsDelivered($applicationId, $data)
    {
        return $this->callApi('PUT', '/financing/v1/applications/' . $applicationId . '/order/deliver', $data);
    }

    /**
     * @param string $applicationId
     * @param null|array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function markOrderItemsAsReturned($applicationId, $data)
    {
        return $this->callApi('PUT', '/financing/v1/applications/' . $applicationId . '/order/return', $data);
    }

    /**
     * @param array $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function precheck($data)
    {
        return $this->callApi('POST', '/financing/v1/precheck', $data);
    }

    /**
     * @return array
     */
    public function apiHealthCheck()
    {
        return $this->callApi('GET', '/v1/health');
    }


    /**
     * Generic API call with error logging
     * @param string $method
     * @param string $endpoint
     * @param array|null $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function callApi($method, $endpoint, $data = null)
    {
        $headers = ['Accept' => 'application/json'];
        if ($this->accessToken != null) {
            $headers += ['Authorization' => 'Bearer ' . $this->accessToken];
        }
        $options = ['headers' => $headers];
        if ($method != 'GET' && empty($data)) {
            $data = new \stdClass(); // at least empty json class is required
        }
        if ($data != null) {
            $options['json'] = $data;
        }

        try {
            $response = $this->client->request($method, $endpoint, $options);
            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            if ($this->logger != null) {
                $this->logger->notice(get_class($this) . ' ' . $e->getMessage());
            }
            throw $e;
        }
    }
}
