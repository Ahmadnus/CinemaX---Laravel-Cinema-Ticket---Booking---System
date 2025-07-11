<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScreening extends FormRequest
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
                'movie_id' => 'nullable',
                'hall_id' => 'nullable',
                'screening_time' => 'nullable',
                'language' => 'nullable',

                'cinema_id' => 'required|exists:cinemas,id',

        ];
    }
}
