<?php

namespace App\Http\Requests;

use App\Models\Post;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreExampleRequest extends FormRequest
{
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
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'user_id' => ['required', Rule::exists(User::class, 'id')],
            'is_published' => ['sometimes', 'boolean'],
            'published_at' => ['sometimes', 'date'],
            'slug' => ['sometimes', 'string', 'max:255', Rule::unique(Post::class, 'slug')],
            'views' => ['sometimes', 'integer', 'min:0'],
            'featured_image' => ['sometimes', 'string', 'max:255'],
            'excerpt' => ['sometimes', 'string'],
            'metadata' => ['sometimes', 'json'],
        ];
    }
}
