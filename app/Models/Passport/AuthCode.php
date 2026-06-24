<?php

namespace App\Models\Passport;

use Laravel\Passport\AuthCode as PassportAuthCode;

class AuthCode extends PassportAuthCode
{
    protected $table = 'mg_oauth_auth_codes';
}
