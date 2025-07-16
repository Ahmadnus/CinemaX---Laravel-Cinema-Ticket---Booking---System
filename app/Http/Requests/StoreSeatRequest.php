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
            'seats'              => 'required|array|min:1',
            'seats.*.row'        => 'required|string|max:10',
            'seats.*.number'     => 'required|integer|min:1',
            'seats.*.price'      => 'required|numeric|min:0',
        ];
    }

    public function withValidator($validator)
{
    $validator->after(function ($validator) {
        $seats = $this->input('seats', []);
        $seen = [];

        foreach ($seats as $index => $seat) {
            $key = strtolower($seat['row']) . '-' . $seat['number'];

            if (in_array($key, $seen)) {
                $validator->errors()->add("seats.$index.number", trans('validation.duplicate_seat'));
            }

            $seen[] = $key;
        }
    });
}

public function messages(): array
{
    return [
        'seats.required'             => trans('validation.seats_required'),
        'seats.array'                => trans('validation.seats_array'),
        'seats.min'                  => trans('validation.seats_min'),

        'seats.*.row.required'       => trans('validation.seat_row_required'),
        'seats.*.row.string'         => trans('validation.seat_row_string'),
        'seats.*.row.max'            => trans('validation.seat_row_max'),

        'seats.*.number.required'    => trans('validation.seat_number_required'),
        'seats.*.number.integer'     => trans('validation.seat_number_integer'),
        'seats.*.number.min'         => trans('validation.seat_number_min'),

        'seats.*.price.required'     => trans('validation.seat_price_required'),
        'seats.*.price.numeric'      => trans('validation.seat_price_numeric'),
        'seats.*.price.min'          => trans('validation.seat_price_min'),
    ];
}
}
