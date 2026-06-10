<?php

namespace App\Http\Requests\Api\Cs2\Standings;

use Illuminate\Foundation\Http\FormRequest;

class ShowStandingRequest extends FormRequest
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
