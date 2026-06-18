<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $userId = $this->route('user') ?? $this->route('idOrSlug');
        if ($userId) {
            return $this->user() && $this->user()->can('update users');
        }

        return $this->user() && $this->user()->can('create users');
    }

    public function rules(): array
    {
        $userId = $this->route('user') ?? $this->route('idOrSlug');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email'.($userId ? ','.$userId : ''),
            'username' => 'nullable|string|max:255|unique:users,username'.($userId ? ','.$userId : ''),
            'is_active' => 'sometimes|boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name', // Accepts role names

            // Nested UserDetail Rules
            'details' => 'nullable|array',
            'details.phone_number' => 'nullable|string|max:20',
            'details.address' => 'nullable|string',
            'details.identity_number' => 'nullable|string|max:50',
            'details.transaction_pin' => 'nullable|string|max:255',
        ];

        if (! $userId) {
            $rules['password'] = 'required|string|min:8';
        } else {
            $rules['password'] = 'nullable|string|min:8';
        }

        return $rules;
    }
}
