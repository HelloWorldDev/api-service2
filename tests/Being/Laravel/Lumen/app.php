<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(sys_get_temp_dir());

$app->withFacades();
$app->withEloquent();

$app->configure('app');
$app->configure('filesystems');
$app->configure('robots');

$app->instance('path.storage', sys_get_temp_dir());

return $app;
