<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignInRequest extends FormRequest
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
            'username' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'
            ],
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'The username field is required.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least :min characters.',
            'password.regex' => 'The password must contain at least one letter and one number.',
        ];
    }
}
