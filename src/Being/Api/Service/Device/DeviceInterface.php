<?php

namespace Being\Api\Service\Device;

interface DeviceInterface
{
    /**
     * @param Device $device
     * @return bool
     */
    public function save(Device $device);

    /**
     * @param $uid
     * @return Device[]|null
     */
    public function find($uid);

    /**
     * @param $uidList
     * @return Device[]|null
     */
    public function pushTokens($uidList);
}
