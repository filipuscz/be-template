<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('view users');
    }

    public function rules(): array
    {
        return [];
    }
}
