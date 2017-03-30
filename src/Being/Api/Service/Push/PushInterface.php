<?php

namespace Being\Api\Service\Push;

interface PushInterface
{
    public function push(array $messages);
}
