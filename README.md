# BEING API SERVICE &nbsp;&nbsp;&nbsp; [![CircleCI](https://circleci.com/gh/HelloWorldDev/api-service.svg?style=svg)](https://circleci.com/gh/HelloWorldDev/api-service) [![Build Status](https://travis-ci.org/HelloWorldDev/api-service.svg?branch=master)](https://travis-ci.org/HelloWorldDev/api-service)

being base services include helper, live, resource and so on.

## [Composer HomePage](https://packagist.org/packages/being/api-service)

```
composer require "being/api-service:~1.0"
```

## UnitTest

```
$ cd api-service
$ composer install
$ composer require "laravel/framework:5.1.*"
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
