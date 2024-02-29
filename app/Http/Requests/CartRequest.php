<?php

namespace App\Http\Requests;

use App\Http\Resources\MessageResource;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CartRequest extends FormRequest
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
            'food_id' => 'required|integer'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        MessageResource::error(400, "Sign Up Failed", $validator->getMessageBag());
    }
}
