## Being Database Cache Component

extends angejia/pea, use for database results cache.

## Laravel & Lumen Support

Configuration:

```
config/database.php
'redis_cache' => [
    'cluster' => true,
    'options' => ['cluster' => 'redis'],
    'node1' => [
        'host'     => env('REDIS_HOST', '127.0.0.1'),
        'port'     => env('REDIS_PORT', 6379),
        'database' => env('REDIS_DATABASE', 0),
        'password' => env('REDIS_PASSWORD', null),
    ],
    'node2' => [
        'host'     => env('REDIS_HOST', '127.0.0.1'),
        'port'     => env('REDIS_PORT', 6380),
        'database' => env('REDIS_DATABASE', 0),
        'password' => env('REDIS_PASSWORD', null),
    ],
],
'redis' => [
    'cluster' => false,
    'default' => [
        'host'     => '127.0.0.1',
        'port'     => 6379,
        'database' => 0,
    ],
    'pea' => [
        'host'     => '127.0.0.1',
        'port'     => 6379,
        'database' => 1,
    ],
],
// 1. when key "redis_cache" not exists, use key "redis" instead
// 2. if use key "redis", when key "pea" not exists, use key "default" instead
```

Laravel Register:

```
$app->register(Being\CacheModel\ServiceProvider::class);
```

Lumen Register:

```
/*
// cause by class "Schame"
$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);
*/
$app = new Being\Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);
$app->register(Being\CacheModel\LumenServiceProvider::class);
```

Usage:

```
class UserModel extends \Being\CacheModel\Model
{
    protected $needCache = true; // cache switch
    protected $table = 'user';
    public $timestamps = false;
}
```