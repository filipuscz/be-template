<?php

namespace App\Http\Requests\Example;

use App\Http\Requests\BaseBulkUpdateRequest;

class BulkUpdateRequest extends BaseBulkUpdateRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('update examples');
    }
}
