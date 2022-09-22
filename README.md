# du-pco-php-sdk

## Install
```shell
composer require digital-union/dupco
```

## Usage
/example/index.php
```php
require __DIR__ .'/../vendor/autoload.php';

use DigitalUnion\Client;

$client = new Client('clientId', 'ky', 'randomStringSecretVal');

$resp = $client->call('/geofence/v1/list_fence', []);

var_dump($resp);
```

```shell
php example/index.php
```
