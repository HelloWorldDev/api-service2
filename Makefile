test:   composer-backup     \
        composer-install    \
        test-cs-fixer       \
        test-service        \
        test-laravel        \
        test-lumen          \
        composer-revert

composer-backup:
	cp composer.json composer.json.bak

composer-revert:
	mv -f composer.json.bak composer.json

composer-install:
	composer install --no-interaction --prefer-source

test-cs-fixer:
	php vendor/bin/php-cs-fixer fix src
	php vendor/bin/php-cs-fixer fix tests

test-service:
	php vendor/bin/phpunit  --bootstrap vendor/autoload.php tests/Being/Services/ResourceServiceTest.php

test-laravel:
	rm -rf vendor composer.lock
	composer require "laravel/framework:5.1.*"
	make composer-install
#	php vendor/bin/phpunit  --bootstrap vendor/autoload.php tests/Being/Services/App/AppServiceTest.php
	php vendor/bin/phpunit  --bootstrap vendor/autoload.php tests/Being/WeiboOpenApi/LaravelServiceProviderTest.php
	php vendor/bin/phpunit  --bootstrap vendor/autoload.php tests/Being/QQOpenApi/LaravelServiceProviderTest.php
	php vendor/bin/phpunit  --bootstrap vendor/autoload.php tests/Being/CacheModel/ModelTest.php
#	make uninstall-laravel

test-lumen:
	rm -rf vendor composer.lock
	composer require "laravel/lumen-framework:5.1.*"
	make composer-install
	php vendor/bin/phpunit  --bootstrap vendor/autoload.php tests/Being/Services/App/AppServiceTest.php
	php vendor/bin/phpunit  --bootstrap vendor/autoload.php tests/Being/WeiboOpenApi/LumenServiceProviderTest.php
	php vendor/bin/phpunit  --bootstrap vendor/autoload.php tests/Being/QQOpenApi/LumenServiceProviderTest.php
	php vendor/bin/phpunit  --bootstrap vendor/autoload.php tests/Being/CacheModel/ModelTest.php
#	make uninstall-lumen

uninstall-illuminate:
	rm -rf vendor/laravel
	rm -rf vendor/illuminate

uninstall-laravel: uninstall-illuminate
	composer remove laravel/framework

uninstall-lumen: uninstall-illuminate
	composer remove laravel/lumen-framework
