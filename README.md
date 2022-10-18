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

$client = new DataClient('cloud-test', 'aa', 'yDpDEihpUsF_RyWsCES1H');
//$client->enableTestMode();

$resp = $client->call('idmap-query-all', [
    'f' => 'mac,imei',
    'k' => '868862032205613',
    'm' => '0',
]);

var_dump($resp);
```

```shell
php example/index.php
```
