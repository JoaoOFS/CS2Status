<?php

namespace App\Http\Requests\Api\Cs2\Matches;

use Illuminate\Foundation\Http\FormRequest;

class ListMatchesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'string', 'max:50'],
            'round' => ['sometimes', 'integer', 'min:1', 'max:10'],
            'record_group' => ['sometimes', 'string', 'max:10'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
