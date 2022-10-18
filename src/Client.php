<?php

namespace DigitalUnion;

use DigitalUnion\utils\Encrypt;
use DigitalUnion\utils\Request;
use DigitalUnion\utils\Response;
use GuzzleHttp\Exception\GuzzleException;

class Client
{
    /**
     * @var string identify of customer
     */
    public $clientId;

    /**
     * @var string key of secret
     */
    public $secretKey;

    /**
     * @var string value of secret
     */
    public $secretVal;

    /**
     * @var string
     */
    public $domain;

    /**
     * @var string
     */
    public $runtimeMode;

    /**
     * @param $clientId string
     * @param $secretKey string
     * @param $secretVal string
     */
    public function __construct($clientId, $secretKey, $secretVal)
    {
        $this->clientId = $clientId;
        $this->secretKey = $secretKey;
        $this->secretVal = $secretVal;
        $this->runtimeMode = sdkVer;
    }

    /**
     * @return void
     */
    public function enableTestMode()
    {
        $this->runtimeMode = sdkVerForTest;
    }

    /**
     * @param $apiId string api id
     * @param $body array api request body
     * @return string
     */
    public function call($apiId, $body)
    {
        $header = [];
        $header[clientId] = $this->clientId;
        $header[secretKey] = $this->secretKey;
        $header[apiIdKey] = $apiId;

        $encrypt = new Encrypt();
        $encryptBody = '';
        if (is_array($body) && count($body) > 0) {
            $encryptBody = $encrypt->encode(json_encode($body), $this->secretVal);
        }

        // GuzzleHttp will throws exceptions when the response status code >= 400
        // https://docs.guzzlephp.org/en/latest/handlers-and-middleware.html#handlers
        try {
            $resp = (new Request())->http($this->domain, httpMethodPost, $header, $encryptBody, $this->runtimeMode);
        } catch (GuzzleException $e) {
            $statusCode = $e->getCode();
            // http response status code > 400
            if ($statusCode > 400) {
                return $this->setResponse(otherErrorCode, sprintf(fmtHttpCodeError, $statusCode));
            }
            // http response status code == 400
            $msg = $e->getMessage();
            $start = strpos($msg, '{');
            $end = strpos($msg, '}');
            if ($start && $end && $start < $end) {
                $msgArray = json_decode(substr($msg, $start, $end-$start+1), true);
                return $this->setResponse($msgArray['code'] ?? otherErrorCode, $msgArray['msg'] ?? $msg);
            }
            return $this->setResponse(otherErrorCode, sprintf(fmtHttpCodeError, $statusCode));
        }

        // http response status code < 400
        $statusCode = $resp->getStatusCode();
        $respContent = $resp->getBody()->getContents();
        if ($statusCode == 200 && strlen($respContent) > 0) {
            $respDecode = $encrypt->decode($respContent, $this->secretVal);
            if ($respDecode === false) {
                $hint = 'Decrypt response data error, probably the secret value is incorrect. '.
                    'If the secret value is correct, something else went error.';
                return $this->setResponse(otherErrorCode, $hint);
            }
            $msgArray = json_decode($respDecode, true);
            if (is_null($msgArray)) {
                switch ($respDecode) {
                    case pageNotFoundErr:
                        return $this->setResponse(pathErrorCode, pageNotFoundMsg);
                    default :
                        return $this->setResponse(otherErrorCode, $respDecode);
                }
            }
            return $this->setResponse($msgArray['code'], $msgArray['msg'] ?? null, $msgArray['data'] ?? null);
        }

        return $this->setResponse(otherErrorCode, 'Unknown error occurred.');
    }

    /**
     * @param $code integer
     * @param $msg string
     * @param $data mixed
     * @return string
     */
    private function setResponse($code, $msg, $data = null)
    {
        return json_encode(new Response($code, $msg, $data));
    }
}
