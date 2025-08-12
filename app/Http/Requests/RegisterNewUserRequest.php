<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterNewUserRequest extends FormRequest
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
            'username' => ['required', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'confirmation_password' => ['required', 'same:password'],
            'role' => ['required', 'in:user,admin'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'obrigatório',
            'username.max' => 'ultrapassou o limite de 50 caracteres',
            'email.required' => 'obrigatório',
            'email.email' => 'inválido',
            'email.unique' => 'inválido',
            'password.required' => 'obrigatório',
            'password.min' => 'não atingiu o mínmo de :min caracteres',
            'confirmation_password.required' => 'obrigatório',
            'confirmation_password.same' => 'as senhas não coincidem',
            'role.required' => 'obrigatório',
            'role.in' => 'inválido',
        ];
    }
}
