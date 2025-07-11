<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
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
            'title'         => 'sometimes|required|string|max:255',
            'description'   => 'sometimes|nullable|string',
            'genre'         => 'sometimes|required|in:action,drama,horror,comedy,romance,sci-fi,other',
            'language'      => 'sometimes|required|string|max:50',
            'duration_min'  => 'sometimes|required|integer|min:1',
            'rating'        => 'sometimes|nullable|numeric|min:0|max:10',
            'release_date'  => 'sometimes|required|date',


            'poster_url'    => 'sometimes|nullable|file|mimes:jpg,jpeg,png|max:2048',
            'trailer_url'   => 'sometimes|nullable|file',
        ];
    }
}
