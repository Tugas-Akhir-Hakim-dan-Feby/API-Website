<?php

namespace App\Http\Requests\Article;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ArticleRequestUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'article_title' => 'required|max:50',
            'article_content' => 'required',
            'document' => 'image|mimes:png,jpg,jpeg,gif'
        ];
    }

    public function messages()
    {
        return [
            'article_title.required' => 'kolom judul wajib diisi',
            'article_title.max' => 'judul maksimal :max kata',
            'article_content.required' => 'kolom konten wajib diisi',
            'document.image' => 'harap masukan gambar valid',
            'document.mimes' => 'harap masukan gambar dengan tipe :values',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'status' => 'WARNING',
            'messages' => $validator->errors(),
            'status_code' => JsonResponse::HTTP_UNPROCESSABLE_ENTITY
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);

        throw new ValidationException($validator, $response);
    }
}
