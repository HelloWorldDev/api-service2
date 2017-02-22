<?php

namespace Being\Services\App\Jobs;

use Being\Services\App\AppService;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendMail extends Job implements SelfHandling, ShouldQueue
{
    use SerializesModels;

    protected $email;
    protected $subject;
    protected $view;
    protected $data;
    protected $config;

    public function __construct($email, $subject, $view, $data, $config)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->view = $view;
        $this->data = $data;
        $this->config = $config;
    }

    public function handle()
    {
        $ret = $this->sendMail();
        AppService::debug(sprintf('mail to %s with subject %s %s', $this->email, $this->subject, $ret ? 'success' : 'failed'),
            __FILE__, __LINE__);
    }

    protected function sendMail()
    {
        return Mail::send($this->view, $this->data, function ($message) {
            $message->to($this->email, isset($this->config['name']) ? $this->config['name'] : null);
            $message->subject($this->subject);
            $fromAddress = isset($this->config['from_address']) ? $this->config['from_address'] : env('MAIL_FROM_ADDRESS');
            if (strlen($fromAddress) > 0) {
                $fromName = isset($this->config['from_name']) ? $this->config['from_name'] : env('MAIL_FROM_NAME');
                $message->from($fromAddress, $fromName);
            }
            if (isset($this->config['cc']) && is_array($this->config['cc'])) {
                foreach ($this->config['cc'] as $cc) {
                    $message->cc($cc['address'], isset($cc['name']) ? $cc['name'] : null);
                }
            }
        });
    }
}
