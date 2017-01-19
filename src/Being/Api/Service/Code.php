<?php

namespace Being\Api\Service;

class Code
{
    // GO与PHP的公共部分是 0-10599
    const SUCCESS = 0;
    // 内部错误 400-499
    const INVALID_PARAM = 10400;
    const REQUEST_TIMEOUT = 10401;
    // 外部错误 500-599
    const SYSTEM_ERROR = 10500;
    const EMPTY_BODY = 10501;
    // GO端业务错误 600-799
    // 非GO端业务错误 800-999
}