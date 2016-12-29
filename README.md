# BEING API SERVICE &nbsp;&nbsp;&nbsp; [![Build Status](https://travis-ci.org/HelloWorldDev/api-service.svg?branch=master)](https://travis-ci.org/HelloWorldDev/api-service)

being base services include helper, live, resource and so on.

## How to use

This package isn't in packagist.phpcomposer.com yet, so use it like this:
Add this configuration in your composer.json

```
"minimum-stability": "dev",
"repositories": [
    {"type": "composer", "url": "http://packagist.phpcomposer.com"},
    {"type": "vcs","url": "https://github.com/HelloWorldDev/api-service"},
    {"packagist": false}
]
```

and run composer command

```shell
composer require being/api-service

```

## UnitTest

```
$ cd api-service
$ composer install
$ php vendor/bin/phpunit --configuration phpunit.xml.dist
```

## App

```
class AppService
{
    public static function limit($request, $default = 10, $max = 100, $key = 'limit'){}
}
```

## Resource

```
class ResourceService
{
    public static function url2key($url){}
}
```
