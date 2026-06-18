<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class DestroyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->can('delete users');
    }

    public function rules(): array
    {
        return [];
    }
}
