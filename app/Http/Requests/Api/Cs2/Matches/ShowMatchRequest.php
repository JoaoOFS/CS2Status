<?php

namespace App\Http\Requests\Api\Cs2\Matches;

use Illuminate\Foundation\Http\FormRequest;

class ShowMatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
