# BEING API SERVICE &nbsp;&nbsp;&nbsp; [![CircleCI](https://circleci.com/gh/HelloWorldDev/api-service.svg?style=svg)](https://circleci.com/gh/HelloWorldDev/api-service) [![Build Status](https://travis-ci.org/HelloWorldDev/api-service.svg?branch=master)](https://travis-ci.org/HelloWorldDev/api-service)

being base services include helper, live, resource and so on.

## How to Install

[Composer HomePage](https://packagist.org/packages/being/api-service)

```
composer require "being/api-service:~1.0"
```

## How to Commit

```
php vendor/bin/php-cs-fixer fix src && php vendor/bin/php-cs-fixer fix tests
git add .
git commit -m "your niubility message"
git push origin master
```

## UnitTest

```
$ cd api-service
$ make test
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
