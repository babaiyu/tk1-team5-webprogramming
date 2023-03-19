<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VideoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'video' => 'required|file|mimetypes:video/mp4',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation Errors',
            'data' => $validator->errors(),
        ]));
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required!',
            'description.required' => 'Description is required!',
            'video.required' => 'Video is required!',
        ];
    }
}
