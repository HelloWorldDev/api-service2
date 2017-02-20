<?php

namespace Being\Push;

use Illuminate\Queue\SerializesModels;

abstract class Message
{
    use SerializesModels;

    const ENVIRONMENT_SANDBOX = 1;
    const ENVIRONMENT_PRODUCTION = 0;

    protected $env;
    protected $to;
    protected $title;
    protected $description;

    public function __construct($to, $title, $description = null)
    {
        $this->to = $to;
        $this->title = $title;
        $this->description = $description;
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
