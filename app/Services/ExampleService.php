<?php

namespace App\Services;

use App\Models\Post;
use App\Services\BaseService;

class ExampleService extends BaseService
{
    public function __construct(private Post $example)
    {
        parent::__construct($example);
    }
}