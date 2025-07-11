<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
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
            'seat_ids'     => 'nullable|array|min:1',
            'seat_ids.*'   => 'exists:seats,id',
            'snacks'       => 'nullable|array',
            'snacks.*.snack_id' => 'required|exists:snacks,id',
            'snacks.*.quantity' => 'required|integer|min:1',
            'payment_method'    => 'nullable|string|in:cash,card,apple_pay',
            'status'       => 'nullable|in:pending,confirmed,canceled'
        ];
    }
}
