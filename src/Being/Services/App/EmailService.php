<?php

namespace Being\Services\App;

use Illuminate\Support\Facades\Mail;
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
        AppService::debug('send email sync'.json_encode($data),__FILE__,__LINE__);
        try {
            $ret = Mail::send($view, $data, function ($message) use ($email, $config, $subject) {
                $message->to($email, isset($config['name']) ? $config['name'] : null);
                $message->subject($subject);
                $fromAddress = isset($config['from_address']) ? $config['from_address'] : env('MAIL_FROM_ADDRESS');
                if (strlen($fromAddress) > 0) {
                    $fromName = isset($config['from_name']) ? $config['from_name'] : env('MAIL_FROM_NAME');
                    $message->from($fromAddress, $fromName);
                }
                if (isset($config['cc']) && is_array($config['cc'])) {
                    foreach ($config['cc'] as $cc) {
                        $message->cc($cc['address'], isset($cc['name']) ? $cc['name'] : null);
                    }
                }
            });
            return $ret;
        } catch (Exception $e) {
            AppService::error(sprintf('send mail to %s with subject %s %s', $email, $subject, 'failed'),
                __FILE__, __LINE__);
        }

        return false;
    }

    public static function sendHtmlMailQueue($email, $subject, $view, $data, $config)
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