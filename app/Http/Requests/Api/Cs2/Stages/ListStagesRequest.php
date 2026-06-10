<?php

namespace App\Http\Requests\Api\Cs2\Stages;

use Illuminate\Foundation\Http\FormRequest;

class ListStagesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'format' => ['sometimes', 'string', 'max:50'],
            'status' => ['sometimes', 'string', 'max:50'],
        ];
    }
}
