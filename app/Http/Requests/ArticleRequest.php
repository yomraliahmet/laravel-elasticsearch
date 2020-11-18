<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;

class ArticleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'detail' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'Title',
            'detail' => 'Detail'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        if (!$this->ajax() && !$this->wantsJson()) {
            parent::failedValidation($validator);
        }

        $errors = $validator->errors()->getMessages();
        $error = Arr::first($errors);
        $errorMessage = Arr::first($error);

        $error = [
            'code'    => 'validation',
            'title'   => "Error",
            'message' => $errorMessage,
            'data' => $errors
        ];

        $response = response()->json($error, 400);

        throw new HttpResponseException($response);
    }
}
