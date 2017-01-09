<?php

namespace Being\CacheModel;

use Angejia\Pea\Cache;
use Angejia\Pea\Meta;
use Angejia\Pea\RedisMeta;
use Angejia\Pea\RedisCache;
use Angejia\Pea\SchemaFacade;
use Illuminate\Redis\Database;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\AliasLoader;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Schema', SchemaFacade::class);
    }

    public function register()
    {
        $this->app->singleton(RedisCache::class, function () {
            $config = config('database.redis_cache');
            if (!empty($config)) {
                return (new Database($config))->connection();
            } else {
                return Redis::connection('pea') ? : Redis::connection();
            }
        });
        $this->app->singleton(Meta::class, function () {
            return new RedisMeta(app(RedisCache::class));
        });
        $this->app->singleton(Cache::class, function () {
            return new RedisCache(app(RedisCache::class));
        });
    }
}
