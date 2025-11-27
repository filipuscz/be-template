<?php

namespace App\Http\Requests;

use App\Enums\QueryAcceptedComparatorEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BaseIndexRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => 'string',
            'orderByColumns' => 'string',
            'any' => 'in:true,false,1,0',
            'limit' => 'integer',
            'comparator' => Rule::in(array_map(fn($e) => $e->value, QueryAcceptedComparatorEnum::cases())), // add QueryAcceptedComparatorEnum
            'page' => 'integer',
            'filters' => ['nullable', 'array'],
            'filters.*' => ['nullable'],        // can be string or array
            'filters.*.*' => ['nullable'],
            'special_sort' => 'sometime|in:random',
        ];
    }
}
