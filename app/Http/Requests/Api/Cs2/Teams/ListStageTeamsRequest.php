<?php

namespace App\Http\Requests\Api\Cs2\Teams;

use Illuminate\Foundation\Http\FormRequest;

class ListStageTeamsRequest extends FormRequest
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
