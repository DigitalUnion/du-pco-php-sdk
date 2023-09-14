<?php

require __DIR__ .'/../vendor/autoload.php';

use DigitalUnion\DataClient;
use DigitalUnion\utils\Encrypt;

$clientId = 'cloud-test';
$secretKey = 'aa';
$secretVal = 'yDpDEihpUsF_RyWsCES1H';

$apiId = 'idmap-query-all';
$body = [
    'f' => 'mac,imei',
    'k' => '868862032205613',
    'm' => '0',
];

// ---------- 发送请求 ----------
$client = new DataClient($clientId, $secretKey, $secretVal);
//$client->enableTestMode();

$resp = $client->call($apiId, $body);
var_dump($resp);

// ---------- 加解密 ----------
$encrypt = new Encrypt();

$encode = $encrypt->encode(json_encode($body), $secretVal);
var_dump($encode);

$decode = $encrypt->decode($resp, $secretVal);
var_dump($decode);
