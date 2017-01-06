## WeiboOpenApi

implement weibo open api

## Install

```
composer require "being/api-service:~1.0"
```

## Base Usage

```
$client = new \Being\WeiboOpenApi\WeiboClient('AppKey', 'AppSecret', 'AccessToken', 'RefreshToken');
$userInfo = $client->show_user_by_id('111111');
```

## Laravel Support

Configuration:

```
config/weibo_open_api.php:
<?php

return [
    // AppKey of Weibo
    'wb_akey' => '',
    // AppSecret of Weibo
    'wb_skey' => '',
    // Auth callback url
    'wb_callback_url' => '',
];

```

Register:

```
$app->register(Being\WeiboOpenApi\ServiceProvider::class);
```

Usage:

```
$client = app(WeiboClient::class);
$client->setAccessToken('2.00zZXjvB00m8Ic4374c44bfb43suYB');
$userInfo = $client->show_user_by_id('1769237827');
```

## Lumen Support

Register:

```
$app->register(Being\WeiboOpenApi\LumenServiceProvider::class);
$app->configure('weibo_open_api');
```