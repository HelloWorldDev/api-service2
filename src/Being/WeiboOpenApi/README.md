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

## Lumen Support

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
$app->configure('weibo_open_api');
```