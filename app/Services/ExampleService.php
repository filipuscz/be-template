<?php

namespace App\Services;

use App\Models\Example;

class ExampleService extends BaseService
{
    public function __construct(Example $example)
    {
        parent::__construct($example);
    }
}
