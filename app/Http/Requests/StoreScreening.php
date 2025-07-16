<?php

namespace App\Http\Requests;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class StoreScreening extends FormRequest
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
            'movie_id'       => ['required', 'exists:movies,id'],
            'hall_id'        => ['required', 'exists:halls,id'],
            'screening_time' => ['required', 'date_format:Y-m-d H:i:s'],
            'language'       => ['required', 'string', 'max:10'],
            'cinema_id'      => ['required', 'exists:cinemas,id'],
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $movieId = $this->input('movie_id');
            $hallId = $this->input('hall_id');
            $screeningTime = $this->input('screening_time');

            if ($movieId && $hallId && $screeningTime) {
                $exists = \App\Models\Screening::where('movie_id', $movieId)
                    ->where('hall_id', $hallId)
                    ->where('screening_time', $screeningTime)
                    ->exists();

                if ($exists) {
                    $validator->errors()->add('screening_time', trans('This screening already exists in this hall at the specified time.'));
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'movie_id.required'       => trans('The movie field is required.'),
            'movie_id.exists'         => trans('The selected movie does not exist.'),
            'hall_id.required'        => trans('The hall field is required.'),
            'hall_id.exists'          => trans('The selected hall does not exist.'),
            'screening_time.required' => trans('The screening time is required.'),
            'screening_time.date_format' => trans('The screening time must be a valid date and time (Y-m-d H:i:s).'),
            'language.required'       => trans('The language field is required.'),
            'language.string'         => trans('The language must be a string.'),
            'language.max'            => trans('The language may not be greater than :max characters.'),
            'cinema_id.required'      => trans('The cinema field is required.'),
            'cinema_id.exists'        => trans('The selected cinema does not exist.'),
        ];
    }
}