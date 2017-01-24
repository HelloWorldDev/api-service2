<?php

namespace Being\Api\Service\Device;

class Device
{
    protected $country;
    protected $sim_operator;
    protected $app_version;
    protected $os_version;
    protected $network_country_iso;
    protected $network_operator;
    protected $app_channel;
    protected $sim_country_iso;
    protected $app_version_code;
    protected $package_name;
    protected $os_name;
    protected $lang;
    protected $platform_app_id; // client post app_id
    protected $appstore_id;
    protected $app_build_version;
    protected $device_id; // client post nbid;
    protected $app_bundle_id;
    protected $size;
    protected $network_operator_name;
    protected $access_token;
    protected $device;
    protected $version;

    public static function create($attributes)
    {
        $device = new static;
        foreach ($attributes as $key => $val) {
            if (property_exists($device, $key)) {
                $device->$key = $val;
            }
        }

        return $device;
    }

    public function toArray()
    {
        $vars = get_class_vars(get_class($this));
        $data = [];
        foreach ($vars as $key => $val) {
            $data[$key] = $this->$key;
        }

        return $data;
    }
}