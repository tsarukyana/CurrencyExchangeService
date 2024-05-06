<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExchangeRateRequest extends FormRequest
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
            'date' => 'sometimes|required|date_format:Y-m-d',  // Validate exchange_date if provided
        ];
    }

    public function messages(): array
    {
        return [
            'date.date_format' => 'The date format must be YYYY-MM-DD.',
        ];
    }
}
