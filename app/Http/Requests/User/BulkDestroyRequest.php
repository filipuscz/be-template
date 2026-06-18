<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseBulkDestroyRequest;

class BulkDestroyRequest extends BaseBulkDestroyRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('delete users');
    }
}
