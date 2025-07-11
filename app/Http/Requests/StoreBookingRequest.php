<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
            'screening_id' => 'required|exists:screenings,id',
            'seat_ids'     => 'required|array|min:1',
            'seat_ids.*'   => 'exists:seats,id',
            'snacks'       => 'nullable|array',
            'snacks.*.snack_id' => 'required|exists:snacks,id',
            'snacks.*.quantity' => 'required|integer|min:1',
            'payment_method'    => 'required|string|in:cash,card,apple_pay',
        ];
    }
}
