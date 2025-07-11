<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMovieRequest extends FormRequest
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
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'genre' => 'in:action,drama,horror,comedy,romance,sci-fi,other',
            'language' => 'nullable|string|max:50',
            'duration_min' => 'nullable|integer|min:1',
            'rating' => 'nullable|min:0|max:10',
            'release_date' => 'nullable|date',
            'poster_url' => 'nullable',
            'trailer_url' => 'nullable',
        ];
    }
}
