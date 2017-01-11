<?php

namespace Tests\Being\Services\App;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Container
     */
    protected $app;

    public function setUp()
    {
        if (class_exists('Laravel\Lumen\Application')) {
            $app = new \Laravel\Lumen\Application(sys_get_temp_dir());
        }/* elseif (class_exists('Illuminate\Foundation\Application')) {
            $app = new \Illuminate\Foundation\Application(sys_get_temp_dir());
        }*/
        $app->instance('config', new \Illuminate\Config\Repository());
        $app->useStoragePath(sys_get_temp_dir());
        Facade::setFacadeApplication($app);
        $this->app = $app;
    }
}
