<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'login'      => 'required|string|unique:users',
            'first_name' => 'required|string',
            'last_name'  => 'required|string',
            'birth_date' => 'nullable|date',
            'password'   => 'required|string|min:8',
        ];
    }
}
