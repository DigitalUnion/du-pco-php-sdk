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

// ---------- 解密推送数据 ----------
$encrypt = new Encrypt();

$data = ""; // TODO 替换成需要解密的数据
$decode = $encrypt->decode(base64_decode($data), $secretVal);
var_dump($decode);
