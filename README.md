# [BEING API SERVICE](https://packagist.org/packages/being/api-service) &nbsp;&nbsp;&nbsp; [![Build Status](https://travis-ci.org/HelloWorldDev/api-service.svg?branch=master)](https://travis-ci.org/HelloWorldDev/api-service)

being base services include helper, live, resource and so on.

## How to use

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


```
$ composer require "being/api-service:~1.0"
```


## How to Commit

```
$ php vendor/bin/php-cs-fixer fix src && php vendor/bin/php-cs-fixer fix tests
// test your code by run "make test"
// then
$ git add .
$ git commit -m "your niubility message"
$ git push origin master
```

## UnitTest

```
$ cd api-service
$ make test
```
