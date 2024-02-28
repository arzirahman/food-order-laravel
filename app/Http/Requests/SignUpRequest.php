<?php

namespace App\Http\Requests;

use App\Http\Resources\MessageResource;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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
            'username' => 'required|string|unique:users',
            'fullname' => 'required|string',
            'password' => [
                'required',
                'string',
                'min:6',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'
            ],
            'retypePassword' => 'required|string|same:password',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'The username field is required.',
            'username.unique' => 'The username has already been taken.',
            'fullname.required' => 'The fullname field is required.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least :min characters.',
            'password.regex' => 'The password must contain at least one letter and one number.',
            'retypePassword.required' => 'The retype password field is required.',
            'retypePassword.same' => 'The password and retype password must match.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        MessageResource::error(400, "Sign Up Failed", $validator->getMessageBag());
    }
}
