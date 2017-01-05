<?php

namespace Being\WeiboOpenApi;

use SaeTClientV2;

class WeiboClient extends SaeTClientV2
{
    public function setAccessToken($accessToken)
    {
        $this->oauth->access_token = $accessToken;
    }

    public function setRefreshToken($refreshToken)
    {
        $this->oauth->refresh_token = $refreshToken;
    }
}
