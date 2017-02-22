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
        app('Illuminate\Contracts\Bus\Dispatcher')->dispatch($job);

        return true;
    }

    /**
     * @param $email
     * @return string
     */
    public static function hiddenEmail($email)
    {
        if (!empty($email)) {
            $j = 0;
            for ($i = 0, $l = strlen($email); $i < $l; $i++) {
                if ($email[$i] == '@') {
                    $j = $i;
                    break;
                }
                if ($i >= 3) {
                    $email[$i] = '*';
                }
            }
            if ($j <= 3) {
                if ($j == 1) {
                    $email[0] = '*';
                } else {
                    while (--$j > 0) {
                        $email[$j] = '*';
                    }
                }
            }
        }

        return $email;
    }
}