<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('idOrSlug');
        $rule = 'required|string|max:255|unique:roles,name';

        if ($id) {
            $rule .= ','.$id;
        }

        return [
            'name' => $rule,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ];
    }
}
