<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $table = $this->userModel->getTable();

        return [
            'name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', Rule::unique($table, 'username')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique($table, 'email')],
            'password' => 'required|string|min:8|confirmed:password_confirmation',
            'password_confirmation' => 'required|string|min:8',
        ];
    }
}
