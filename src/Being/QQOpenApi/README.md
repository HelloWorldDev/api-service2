## Where the files download

[http://wiki.open.qq.com/wiki/SDK%E4%B8%8B%E8%BD%BD](http://wiki.open.qq.com/wiki/SDK%E4%B8%8B%E8%BD%BD)


## QQ OpenApiV3 Original Content

[README.txt](./README.txt)


## Laravel & Lumen Support

Configuration:

```
config/qq_open_api.php
<?php

return [
    'app_id' => '',
    'app_key' => '',
    'server_name' => 'openapi.tencentyun.com',
    'pf' => '',
];

```

Laravel Register:

```
$app->register(Being\QQOpenApi\ServiceProvider::class);
```

Lumen Register:

```
$app->register(Being\QQOpenApi\LumenServiceProvider::class);
$app->configure('qq_open_api');
```

Base Usage:

```
$client = new QQClient('app_id', 'app_key');
$client->setServerName('server_host');
$userInfo = $client->getUserInfo('', '', '');
```

Laravel Usage:

```
$client = app(QQClient::class);
$userInfo = $client->getUserInfo('', '', '');
```