<?php

namespace App\Http\Requests\Example;

use App\Models\Example;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $exampleId = $this->route('example') ?? $this->route('idOrSlug');
        if ($exampleId) {
            return $this->user() && $this->user()->can('update examples');
        }

        return $this->user() && $this->user()->can('create examples');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $exampleId = $this->route('example');

        return [
            'uuid' => ['required', 'uuid', Rule::unique(Example::class, 'uuid')->ignore($exampleId)],
            'ulid' => ['required', 'ulid', Rule::unique(Example::class, 'ulid')->ignore($exampleId)],

            'string_column' => ['required', 'string', 'max:255'],
            'string_with_length' => ['required', 'string', 'max:100'],
            'char_column' => ['required', 'string', 'size:4'],
            'text_column' => ['required', 'string'],
            'medium_text_column' => ['required', 'string'],
            'long_text_column' => ['required', 'string'],

            'integer_column' => ['required', 'integer'],
            'big_integer_column' => ['required', 'integer'],
            'medium_integer_column' => ['required', 'integer'],
            'small_integer_column' => ['required', 'integer'],
            'tiny_integer_column' => ['required', 'integer'],
            'unsigned_integer_column' => ['required', 'integer', 'min:0'],

            'decimal_column' => ['required', 'numeric'],
            'double_column' => ['required', 'numeric'],
            'float_column' => ['required', 'numeric'],

            'boolean_column' => ['sometimes', 'boolean'],

            'date_column' => ['required', 'date'],
            'datetime_column' => ['required', 'date'],
            'datetime_tz_column' => ['required', 'date'],
            'time_column' => ['required', 'date_format:H:i:s'],
            'time_tz_column' => ['required', 'string'],
            'timestamp_column' => ['nullable', 'date'],
            'timestamp_tz_column' => ['nullable', 'date'],
            'year_column' => ['required', 'integer', 'min:1901', 'max:2155'],

            'json_column' => ['nullable', 'json'],
            'jsonb_column' => ['nullable', 'json'],
            'binary_column' => ['nullable', 'string'],
            'enum_column' => ['required', Rule::in(['active', 'inactive', 'pending'])],
            'set_column' => ['required', 'string'],

            'ip_address_column' => ['nullable', 'ip'],
            'mac_address_column' => ['nullable', 'mac_address'],

            'geometry_column' => ['nullable', 'string'],
            'point_column' => ['nullable', 'string'],
            'linestring_column' => ['nullable', 'string'],
            'polygon_column' => ['nullable', 'string'],

            'user_id' => ['nullable', Rule::exists(User::class, 'id')],
        ];
    }
}
