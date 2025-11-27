<?php

namespace App\Services;

use App\Models\DivineAdmin\Account;
use App\Models\Example;
use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;

class ExampleService extends BaseService
{
    public function __construct(private User $example)
    {
        parent::__construct($example);
    }
}