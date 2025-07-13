<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSeatRequest extends FormRequest
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
            'seats'           => 'required|array|min:1',
            'seats.*.row'     => 'required|string|max:10',
            'seats.*.number'  => 'required|integer|min:1',
            'seats.*.price'   => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'seats.required' => 'Seat list is required.',
            'seats.*.row.required' => 'Row is required for each seat.',
            'seats.*.number.required' => 'Number is required for each seat.',
            'seats.*.price.required' => 'Price is required for each seat.',
        ];
    }
}
