<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;

class Data extends AbstractHelper
{
    const RAZOYO_API_BASE_URL = "https://exam.razoyo.com/api";
    /**
     * @var Json
     */
    private $json;
    /**
     * @var Curl
     */
    private $curl;

    /**
     * @param Context $context
     */
    public function __construct(
        Context $context,
        Json $json,
        Curl $curl
    )
    {
        parent::__construct($context);
        $this->json = $json;
        $this->curl = $curl;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * Request to zinrelo for specific event URL
     *
     * @param mixed $url
     * @param string $requestType
     * @param mixed $params
     * @return mixed
     */
    public function request($url, $requestType = "get", $params = [])
    {
        try {
            $headers = [
                "content-type" => "application/json",
                "accept" => "application/json"
            ];

            $this->curl->setHeaders($headers);
            if ($requestType == "post") {
                $this->curl->post($url, $params);
            } else {
                if (!empty($params)) {
                    $this->curl->get($url, $params);
                } else {
                    $this->curl->get($url);
                }
            }
            $response = $this->curl->getBody();
            return $this->returnResponseData($response);
        } catch (Exception $e) {
            $this->logger->addErrorLog($e->getMessage());
        }
    }

    /**
     * Decode json response and handle response Data
     *
     * @param mixed $responseData
     * @return array|bool[]
     */
    public function returnResponseData($responseData): array
    {
        $response = (!empty($responseData) && $this->isJson($responseData)) ?
            $this->json->unserialize($responseData) : [];
        if (!empty($response)) {
            if (!isset($response['error_code']) && !isset($response['error'])) {
                $result = [
                    "success" => true,
                    "result" => $response
                ];
            } else {
                $result = [
                    "success" => false,
                    "result" => $response['error']
                ];
            }
        } else {
            $result = [
                "success" => false,
                "result" => __("Something went wrong, check and try again")
            ];
        }
        return $result;
    }

    /**
     * Is Json
     *
     * @param string $string
     * @return bool
     */
    public function isJson($string)
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * @return string
     */
    private function getCarsApi()
    {
        return self::RAZOYO_API_BASE_URL . "/cars";
    }

    /**
     * @param string $id
     * @return string
     */
    private function getCarDetailApi($id = '')
    {
        if($id) {
            return self::RAZOYO_API_BASE_URL . "/cars/".$id;
        }
        return "";
    }

    public function getCars() {
        $response = [];
        $carsApi = $this->getCarsApi();
        $responseData = $this->request($carsApi);
        if($responseData['success']) {
            $response = $responseData['result']['cars'];
        }
        return $response;
    }

    public function getCarDetails($carId) {
        $response = [];
        if($carId) {
            $carDetailsApi = $this->getCarDetailApi($carId);
            $responseData = $this->request($carDetailsApi);
            if($responseData['success']) {
                $response = $responseData['result'];
            }
        }
        return $response;
    }
}

