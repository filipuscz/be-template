<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseBulkUpdateRequest;

class BulkUpdateRequest extends BaseBulkUpdateRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('update users');
    }
}
