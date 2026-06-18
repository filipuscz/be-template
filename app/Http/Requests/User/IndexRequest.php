<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseIndexRequest;

class IndexRequest extends BaseIndexRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('view users');
    }
}
