<?php

namespace Being\Push\Message;

use Being\Push\Message as BaseMessage;
use Being\Services\App\AppService;
use Sly\NotificationPusher\PushManager,
    Sly\NotificationPusher\Adapter\Apns as ApnsAdapter,
    Sly\NotificationPusher\Collection\DeviceCollection,
    Sly\NotificationPusher\Model\Device,
    Sly\NotificationPusher\Model\Message,
    Sly\NotificationPusher\Model\Push;

class ApnsMessage extends BaseMessage
{
    protected $certificateFile;
    protected $env;

    public function __construct($to, $title)
    {
        parent::__construct($to, $title);
    }

    public function setCertificateFile($certificateFile)
    {
        $this->certificateFile = $certificateFile;

        return $this;
    }

    public function send()
    {
        $env = $this->env == self::ENVIRONMENT_SANDBOX ? PushManager::ENVIRONMENT_DEV : PushManager::ENVIRONMENT_PROD;
        $pushManager = new PushManager($env);
        $apnsAdapter = new ApnsAdapter([
            'certificate' => $this->certificateFile,
        ]);
        $devices = new DeviceCollection(array(
            new Device($this->to),
        ));
        $message = new Message($this->title);
        $push = new Push($apnsAdapter, $devices, $message);
        $pushManager->add($push);

        $ret = false;
        $error = null;

        try{
            $pushManager->push();
            $ret = $push->isPushed();
        }catch(\Exception $e) {
            $error = $e->getMessage();
        }

        // log
        if (class_exists('\Being\Services\App\AppService')) {
            $pushInfo = [
                'env' => $this->env == self::ENVIRONMENT_SANDBOX ? 'dev' : 'prod',
                'to' => $this->to,
                'ret' => $ret,
                'error' => $error,
            ];
            $message = sprintf('push:%s', json_encode($pushInfo));
            if ($ret) {
                \Being\Services\App\AppService::debug($message, __FILE__, __LINE__);
            } else {
                \Being\Services\App\AppService::error($message, __FILE__, __LINE__);
            }
        }

        return $ret;
    }
}
