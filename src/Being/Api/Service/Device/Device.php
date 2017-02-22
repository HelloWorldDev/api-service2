<?php

namespace Being\Api\Service\Device;

class Device
{
    const TYPE_UNKNOWN = 0;
    const TYPE_IOS = 1;
    const TYPE_ANDROID = 2;

    protected $device_type;
    protected $device_id;
    protected $push_token;
    protected $uid;
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
    protected $platform_app_id;
    protected $appstore_id;
    protected $app_build_version;
    protected $app_bundle_id;
    protected $size;
    protected $network_operator_name;
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

    public function __get($name)
    {
        return $this->$name;
    }
}
