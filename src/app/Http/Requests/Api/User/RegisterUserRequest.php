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

    public function messages()
    {
        return [
            'login.required' => 'Поле "Логин" обязательно для заполнения.',
            'login.string'   => 'Поле "Логин" должно быть строкой.',
            'login.unique'   => 'Такой логин уже существует.',

            'first_name.required' => 'Поле "Имя" обязательно для заполнения.',
            'first_name.string'   => 'Поле "Имя" должно быть строкой.',

            'last_name.required' => 'Поле "Фамилия" обязательно для заполнения.',
            'last_name.string'   => 'Поле "Фамилия" должно быть строкой.',

            'birth_date.date' => 'Поле "Дата рождения" должно быть датой.',

            'password.required' => 'Поле "Пароль" обязательно для заполнения.',
            'password.string'   => 'Поле "Пароль" должно быть строкой.',
            'password.min'      => 'Пароль должен содержать не менее 8 символов.',
        ];
    }

}
