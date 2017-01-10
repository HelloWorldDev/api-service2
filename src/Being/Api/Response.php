<?php

namespace Being\Api;

class Response
{
    const SUCCESS = 0;

    protected $code = self::SUCCESS;
    protected $message;
    protected $data;

    public function setAsSuccess()
    {
        $this->code = self::SUCCESS;

        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function isSuccess()
    {
        return $this->code === 0;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getData()
    {
        return $this->data;
    }
}