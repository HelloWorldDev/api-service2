test:   composer-backup     \
        composer-install    \
        test-cs-fixer       \
        test-service        \
        test-service-app    \
        test-laravel        \
        test-lumen          \
        composer-revert

composer-backup:
	cp composer.json composer.json.bak

composer-revert:
	mv -f composer.json.bak composer.json

composer-install:
	composer install --no-interaction

test-cs-fixer:
	php vendor/bin/php-cs-fixer fix src
	php vendor/bin/php-cs-fixer fix tests

test-service:
	php vendor/bin/phpunit  --bootstrap vendor/autoload.php tests/Being/Services/ResourceServiceTest.php

test-service-app:
	php vendor/bin/phpunit  --bootstrap vendor/autoload.php tests/Being/Services/App/AppServiceTest.php

test-laravel:
	rm -rf vendor composer.lock
	composer require "laravel/framework:5.1.*"
	composer install --no-interaction
	php vendor/bin/phpunit  --bootstrap vendor/autoload.php tests/Being/WeiboOpenApi/LaravelServiceProviderTest.php
#	make uninstall-laravel

test-lumen:
	rm -rf vendor composer.lock
	composer require "laravel/lumen-framework:5.1.*"
	composer install --no-interaction
	php vendor/bin/phpunit  --bootstrap vendor/autoload.php tests/Being/WeiboOpenApi/LumenServiceProviderTest.php
#	make uninstall-lumen

uninstall-illuminate:
	rm -rf vendor/laravel
	rm -rf vendor/illuminate

uninstall-laravel: uninstall-illuminate
	composer remove laravel/framework

uninstall-lumen: uninstall-illuminate
	composer remove laravel/lumen-framework