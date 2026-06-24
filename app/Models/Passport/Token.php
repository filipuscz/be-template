<?php

namespace App\Models\Passport;

use Laravel\Passport\Token as PassportToken;

class Token extends PassportToken
{
    protected $table = 'mg_oauth_access_tokens';
}
