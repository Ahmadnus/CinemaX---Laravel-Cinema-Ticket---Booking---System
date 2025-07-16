<?php

namespace App\Http\Requests;

use App\Models\Movie;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Validator;
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
            // ترجمة العنوان
            'title.en' => 'required|string|max:255|unique:movie_translations,title,NULL,id,locale,en',
            'title.ar' => 'required|string|max:255|unique:movie_translations,title,NULL,id,locale,ar',

            // ترجمة الوصف
            'description.en' => 'nullable|string',
            'description.ar' => 'nullable|string',

            'genre'         => 'nullable|in:action,drama,horror,comedy,romance,sci-fi,other',
            'language'      => 'nullable|string|max:50',
            'duration_min'  => 'nullable|integer|min:1',
            'rating'        => 'nullable|numeric|min:0|max:10',
            'release_date'  => 'nullable|date',

            // روابط الصور والفيديوهات (يمكن فحص الامتدادات لاحقاً)
            'poster_url'    => 'nullable|url',
            'trailer_url'   => 'nullable|url',
        ];
    }


    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $en = $this->input('title.en');
            $ar = $this->input('title.ar');

            if (Movie::whereTranslation('title', $en)->exists()) {
                $validator->errors()->add('title.en', trans('English type already exists.'));
            }

            if (Movie::whereTranslation('title', $ar)->exists()) {
                $validator->errors()->add('title.ar', trans('Arabic type already exists.'));
            }
        });
    }
    public function messages(): array
    {
        return [
            'title.en.required' => trans('The English title is required.'),
            'title.ar.required' => trans('The Arabic title is required.'),
            'title.en.unique'   => trans('The English title already exists.'),
            'title.ar.unique'   => trans('The Arabic title already exists.'),
        ];
    }
}