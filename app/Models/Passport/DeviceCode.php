<?php

namespace App\Models\Passport;

use Laravel\Passport\DeviceCode as PassportDeviceCode;

class DeviceCode extends PassportDeviceCode
{
    protected $table = 'mg_oauth_device_codes';
}
