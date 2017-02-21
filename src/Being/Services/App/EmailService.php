<?php

namespace Being\Services\App;

use Being\Services\App\Jobs\SendMail;

class EmailService
{
    /**
     * @param $email
     * @param $subject
     * @param $view
     * @param $data
     * @param $config ['from_address' => '', 'from_name' => null, 'name' => null, 'cc' => [
     *  'address' => '', 'name' => null,
     *  'address' => '', 'name' => null,
     * ]]
     * @return bool
     */
    public static function sendHtmlMail($email, $subject, $view, $data, $config)
    {
        $job = new SendMail($email, $subject, $view, $data, $config);
        return app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job) ? true : false;
    }
}