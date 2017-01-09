<?php

namespace Being\CacheModel;

use Angejia\Pea\SchemaFacade;

class LumenServiceProvider extends ServiceProvider
{
    public function boot()
    {
        class_alias(SchemaFacade::class, 'Schema');
    }
}
