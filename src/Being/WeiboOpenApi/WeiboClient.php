<?php

namespace Being\WeiboOpenApi;

use SaeTClientV2;

class WeiboClient extends SaeTClientV2
{
    public function setClientId($clientId)
    {
        $this->oauth->client_id = $clientId;
    }

    public function setClientSecret($clientSecret)
    {
        $this->oauth->client_secret = $clientSecret;
    }

    public function setAccessToken($accessToken)
    {
        $this->oauth->access_token = $accessToken;
    }

    public function setRefreshToken($refreshToken)
    {
        $this->oauth->refresh_token = $refreshToken;
    }
}
