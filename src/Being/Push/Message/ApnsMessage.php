<?php

namespace Being\Push\Message;

use ApnsPHP_Message;
use ApnsPHP_Push;
use Being\Push\Message;
use Being\Services\App\AppService;

class ApnsMessage extends Message
{
    /**
     * @var ApnsPHP_Push
     */
    protected $push;
    protected $certificateFile;
    /**
     * @var ApnsPHP_Message
     */
    protected $message;
    protected $env;

    public function __construct($to, $title)
    {
        parent::__construct($to, $title);
        $this->buildMessage();
    }

    protected function buildMessage()
    {
        $message = new ApnsPHP_Message($this->to);
        // $message->setCustomIdentifier("Message-Badge-3");
        $message->setBadge(1);
        $message->setText($this->title);
        $message->setSound();
        // $message->setCustomProperty('acme2', array('bang', 'whiz'));
        // $message->setCustomProperty('acme3', array('bing', 'bong'));
        $message->setExpiry(7 * 86400);
        $this->message = $message;
    }

    public function setCertificateFile($certificateFile)
    {
        $this->certificateFile = $certificateFile;
        $this->push = new ApnsPHP_Push($this->env, $certificateFile);

        return $this;
    }

    public function setBadge($badge)
    {
        $this->message->setBadge($badge);

        return $this;
    }

    public function setCustomIdentifier($customIdentifier)
    {
        $this->message->setCustomIdentifier($customIdentifier);

        return $this;
    }

    public function setCustomProperty($sName, $mValue)
    {
        $this->message->setCustomProperty($sName, $mValue);

        return $this;
    }

    public function setExpiry($second)
    {
        $this->message->setExpiry($second);

        return $this;
    }


    public function send()
    {
        if (is_null($this->push)) {
            throw new \Exception('call ApnsMessage::setCertificateFile($file) to build a ApnsPHP_Push object');
        }

        // Instantiate a new ApnsPHP_Push object
        // $this->push->setRootCertificationAuthority('entrust_root_certification_authority.pem');
        $this->push->connect();
        $this->push->add($this->message);
        $this->push->send();
        $this->push->disconnect();
        $errors = $this->push->getErrors();
        $ret = count($errors) == 0;

        // log
        if (class_exists('\Being\Services\App\AppService')) {
            $pushInfo = [
                $this->env == self::ENVIRONMENT_SANDBOX ? 'sandbox' : 'production',
                'certificateFile' => $this->certificateFile
            ];
            $message = sprintf('push:%s message:%s errors:%s',
                json_encode($pushInfo),
                $this->message->__toString(),
                json_encode($errors)
            );
            if ($ret) {
                \Being\Services\App\AppService::debug($message, __FILE__, __LINE__);
            } else {
                \Being\Services\App\AppService::error($message, __FILE__, __LINE__);
            }
        }

        return $ret;
    }
}
