<?php

namespace Being\Laravel\Lumen;

use Illuminate\Support\Facades\Facade;

class Application extends \Laravel\Lumen\Application
{
    public function withFacades()
    {
        Facade::setFacadeApplication($this);

        if (! static::$aliasesRegistered) {
            static::$aliasesRegistered = true;

            class_alias('Illuminate\Support\Facades\App', 'App');
            class_alias('Illuminate\Support\Facades\Auth', 'Auth');
            class_alias('Illuminate\Support\Facades\Bus', 'Bus');
            class_alias('Illuminate\Support\Facades\DB', 'DB');
            class_alias('Illuminate\Support\Facades\Cache', 'Cache');
            class_alias('Illuminate\Support\Facades\Cookie', 'Cookie');
            class_alias('Illuminate\Support\Facades\Crypt', 'Crypt');
            class_alias('Illuminate\Support\Facades\Event', 'Event');
            class_alias('Illuminate\Support\Facades\Hash', 'Hash');
            class_alias('Illuminate\Support\Facades\Log', 'Log');
            class_alias('Illuminate\Support\Facades\Mail', 'Mail');
            class_alias('Illuminate\Support\Facades\Queue', 'Queue');
            class_alias('Illuminate\Support\Facades\Request', 'Request');
            // class_alias('Illuminate\Support\Facades\Schema', 'Schema');
            class_alias('Illuminate\Support\Facades\Session', 'Session');
            class_alias('Illuminate\Support\Facades\Storage', 'Storage');
            class_alias('Illuminate\Support\Facades\Validator', 'Validator');
        }
    }
}
