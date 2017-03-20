<?php

namespace Being\Push;

abstract class Message
{
    const ENVIRONMENT_SANDBOX = 1;
    const ENVIRONMENT_PRODUCTION = 0;

    protected $env;
    protected $to;
    protected $title;
    protected $description;
    protected $options;

    public function __construct($to, $title, $description = null, $options = null)
    {
        $this->to = $to;
        $this->title = $title;
        $this->description = $description;
        $this->options = $options;
    }

    public function setEnv($env)
    {
        $this->env = $env;

        return $this;
    }


    /**
     * @return boolean
     */
    abstract public function send();
}
