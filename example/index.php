<?php

require __DIR__ .'/../vendor/autoload.php';

use DigitalUnion\DataClient;

$client = new DataClient('cloud-test', 'aa', 'yDpDEihpUsF_RyWsCES1H');
//$client->enableTestMode();

$resp = $client->call('idmap-query-all', [
    'f' => 'mac,imei',
    'k' => '868862032205613',
    'm' => '0',
]);

var_dump($resp);
