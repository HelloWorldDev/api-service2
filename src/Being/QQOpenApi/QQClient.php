<?php

namespace Being\QQOpenApi;

class QQClient extends OpenApiV3
{
    public function getUserInfo($openid, $openkey, $pf = null)
    {
        $params = array(
            'openid' => $openid,
            'openkey' => $openkey,
            'pf' => $pf,
        );

        $script_name = '/v3/user/get_info';

        return $this->api($script_name, $params, 'post');
    }
}
