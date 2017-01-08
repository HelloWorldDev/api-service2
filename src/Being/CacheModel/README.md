## Being Database Cache Component

extends angejia/pea, use for database results cache.

## Laravel & Lumen Support

Configuration:

```
config/database.php
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
        'database' => 2,
    ],
],
// when key "pea" not exists, use key "default" instead
```

Laravel Register:

```
$app->register(Being\CacheModel\ServiceProvider::class);
```

Lumen Register:

```
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