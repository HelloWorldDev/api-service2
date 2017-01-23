<?php

namespace Being\Api\Service;

// GO与PHP的公共部分是 0-10599
// 内部错误 400-499
// 外部错误 500-599
// GO端业务错误 600-799
// 非GO端业务错误 800-999

class Code
{
    const SUCCESS = 0;
    const INVALID_PARAM = 10400;
    const REQUEST_TIMEOUT = 10401;
    const ERROR_CODE_NOT_EXISTS = 10402;
    const SYSTEM_ERROR = 10500;
    const EMPTY_BODY = 10501;
    const USERNAME_EXISTS = 10600;
    const USERNAME_LENGTH = 10601;
    const USERNAME_FORMAT = 10602;
    const EMAIL_EXISTS = 10603;
    const EMAIL_LENGTH = 10604;
    const EMAIL_FORMAT = 10605;
    const FULLNAME_EXISTS = 10606;
    const FULLNAME_LENGTH = 10607;
    const FULLNAME_FORMAT = 10608;
    const PASSWORD_LENGTH = 10609;
    const PASSWORD_FORMAT = 10610;
    const USERNAME_NOT_EXISTS = 10611;
    const EMAIL_NOT_EXISTS = 10612;
    const PASSWORD_NOT_MATCH = 10613;
    const USER_NOT_EXISTS = 10614;
}
