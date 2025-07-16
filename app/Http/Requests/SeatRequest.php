<?php

namespace App\Http\Requests;

use App\Models\Screening;
use Illuminate\Foundation\Http\FormRequest;

class SeatRequest extends FormRequest
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
    public function rules()
{
    return [
        'seats' => 'required|array|min:1',
        'seats.*.row' => 'required|string|max:5',
        'seats.*.count' => 'required|integer|min:1',
        'seats.*.price' => 'required|numeric|min:0',
    ];

}
}
