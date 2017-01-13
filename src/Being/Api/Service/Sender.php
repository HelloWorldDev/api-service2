<?php
/**
 * Created by PhpStorm.
 * User: jason
 * Date: 12/1/2017
 * Time: 4:42 PM
 */

namespace Being\Api\Service;

use GuzzleHttp\Psr7\Request;

interface Sender
{
    public function send(Request $request);
}
