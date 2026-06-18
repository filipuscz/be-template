<?php

namespace App\Http\Requests\Example;

use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('delete examples');
    }

    public function rules(): array
    {
        return [];
    }
}
