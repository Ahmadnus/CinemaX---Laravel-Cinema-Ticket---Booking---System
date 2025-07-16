<?php

namespace App\Http\Requests;

use App\Models\Screening;
use App\Models\Seat;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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

public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $screeningId = $this->input('screening_id');
        $seatIds = $this->input('seat_ids');

        if (!$screeningId || !$seatIds || !is_array($seatIds)) return;

        $invalidSeats = Seat::whereIn('id', $seatIds)
            ->where('screening_id', '!=', $screeningId)
            ->pluck('id')
            ->toArray();

        if (!empty($invalidSeats)) {
            $validator->errors()->add('seat_ids', trans('Some selected seats do not belong to the selected screening.'));
        }
    });
}    }
