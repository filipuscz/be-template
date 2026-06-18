<?php

namespace App\Http\Requests\Example;

use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('view examples');
    }

    public function rules(): array
    {
        return [];
    }
}
