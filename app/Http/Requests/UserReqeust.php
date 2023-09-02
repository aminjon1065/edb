<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserReqeust extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'uuid' => 'required|string|unique',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'full_name' => 'required|string',
            'region' => 'required|string',
            'role' => 'required|integer',
            'password' => 'required|string',
        ];
    }
}
