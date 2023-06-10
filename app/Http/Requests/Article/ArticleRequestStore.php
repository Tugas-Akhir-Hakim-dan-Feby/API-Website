<?php

namespace App\Http\Requests\Article;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class ArticleRequestStore extends FormRequest
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
        $user = User::where('id', request()->id)->first();
        return [
            'name' => 'required|string|max:255',
            'email' => ['email', 'required', Rule::unique('users')->ignore($user)],
            'article_title'=> 'required|string|max:255',
            'article_slug'=> 'required|string|max:255',
            'article_body'=> 'required|string|max:255',
            'status'=> 'required|integer|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'kolom nama wajib diisi',
            'email.required' => 'kolom email wajib diisi',
            'email.email' => 'email tidak valid',
            'email.unique' => 'email sudah terdaftar',
            'article_title.required' => 'kolom judul wajib diisi',
            'article_title.string' => 'judul harus kurang dari 255 karakter',
            'article_title.max' => 'judul harus kurang dari 255 karakter',
            'article_slug.required' => 'slug wajib diisi',
            'article_slug.string' => 'slug harus kurang dari 255 karakter',
            'article_slug.max' => 'slug harus kurang dari 255 karakter',
            'article_body.required' => 'isi artikel wajib diisi',
            'article_body.string' => 'isi artikel harus kurang dari 255 karakter',
            'status.required' => 'status diperlukan',
            'status.integer' => 'harap masukan dalam bentuk bilangan 1/0',
            'status.max' => 'harap masukan dalam bentuk bilangan 1/0',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = new JsonResponse([
            'messages' => $validator->errors(),
            'status_code' => 400
        ], 400);

        throw new ValidationException($validator, $response);
    }
}
