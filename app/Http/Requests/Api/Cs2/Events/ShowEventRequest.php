<?php

namespace App\Http\Requests\Api\Cs2\Events;

use Illuminate\Foundation\Http\FormRequest;

class ShowEventRequest extends FormRequest
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
