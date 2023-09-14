# du-pco-php-sdk

## Install
```shell
composer require digital-union/dupco
```

## Extension

- **openssl**

由于 `php` 原生加密库 `mcrypt` 在 `7.2.0` 版本后被移除，所以使用 `openssl` 扩展进行数据加密。  

**请务必安装并开启 `php` 的 `openssl` 扩展。**  

更多信息请参考：  
https://www.php.net/manual/zh/intro.mcrypt.php  
https://www.php.net/manual/zh/openssl.installation.php

## Usage
example/index.php:
```php
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
```

```shell
php example/index.php
```
