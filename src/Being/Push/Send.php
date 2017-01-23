<?php

namespace Being\Push;

class Send
{
    /**
     * @var Message[]
     */
    protected $messages;

    public function addMessage(Message $message)
    {
        $this->messages[] = $message;

        return $this;
    }

    /**
     * Send All Messages
     * @return bool
     */
    public function send()
    {
        $ret = 1;
        if (is_array($this->messages)) {
            foreach ($this->messages as $message) {
                $ret &= $message->send();
            }
            $this->messages = null;
        }

        return boolval($ret);
    }
}
