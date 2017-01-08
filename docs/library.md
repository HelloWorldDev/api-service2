## Being后端服务包含的库

我们后端在API项目上统一使用Lumen框架, 在WEB项目上统一使用Laravel框架.

所以在集成一些库的时候, 我们会优先选择提供Laravel Provider的库.

下面是我们整理使用中的库, 以避免重复找轮子.

Fame网站项目(Laravel):

```
"require": {
    "php": ">=5.5.9",
    "laravel/framework": "5.1.*",
    "jenssegers/agent": "^2.1",
    "predis/predis": "~1.0",
    "guzzlehttp/guzzle": "~6.0",
    "qiniu/php-sdk": "^7.0",
    "doctrine/dbal": "^2.5",
    "endroid/qrcode": "^1.5",
    "zgldh/qiniu-laravel-storage": "~0.3"
},
"require-dev": {
    "fzaninotto/faker": "~1.4",
    "mockery/mockery": "0.9.*",
    "phpunit/phpunit": "~4.0",
    "phpspec/phpspec": "~2.1"
},
```

Fame后台项目(Lumen):

```
"require": {
    "php": ">=5.5.9",
    "laravel/lumen-framework": "5.1.*",
    "qiniu/php-sdk": "^7.0",
    "davibennun/laravel-push-notification": "dev-laravel5",
    "illuminate/redis": "^5.1",
    "vlucas/phpdotenv": "~1.0",
    "jenssegers/mongodb": "^2.2",
    "pili-engineering/pili-sdk-php": "^1.5",
    "pda/pheanstalk": "~3.1.0",
    "paypal/rest-api-sdk-php" : "*",
    "guzzlehttp/guzzle": "^6.2",
    "aws/aws-sdk-php": "^3.18",
    // Excel的支持
    "maatwebsite/excel": " 2.1.3",
    "aws/aws-sdk-php-laravel": "^3.1"
},
"require-dev": {
    "phpunit/phpunit": "~4.0",
    "fzaninotto/faker": "~1.0"
},
```

FameAPI项目(Lumen):

```
"require": {
    "php": ">=5.5.9",
    "laravel/lumen-framework": "5.1.*",
    "vlucas/phpdotenv": "~1.0",
    "predis/predis": "~1.0",
    "illuminate/redis": "^5.1",
    "qiniu/php-sdk": "^7.0",
    "guzzlehttp/guzzle": "~6.0",
    "giggsey/libphonenumber-for-php": "~7.0",
    "jenssegers/mongodb": "^2.2",
    "swiftmailer/swiftmailer": "^5.4",
    "illuminate/mail": "^5.1",
    "davibennun/laravel-push-notification": "dev-laravel5",
    "endroid/qrcode": "^1.5",
    "overtrue/pinyin": "2.6.7",
    "overtrue/laravel-pinyin": "1.2.*",
    "pda/pheanstalk": "~3.1.0",
    "aws/aws-sdk-php": "^3.18",
    "omnipay/paypal":"~2.0",
    "lokielse/omnipay-wechatpay": "dev-master",
    "bigqq/omnipay-alipay": "dev-master",
    "zengdongbao/ks3-php": " dev-master",
    "paypal/rest-api-sdk-php": "*",
    "being/api-service": "~1.0"
},
"require-dev": {
    "phpunit/phpunit": "~4.0",
    "fzaninotto/faker": "~1.0",
    "doctrine/dbal": "^2.5"
},
```
