<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthenticationRequest extends FormRequest
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
            'email' => ['required', 'email', 'min:11', 'exists:users,email'],
            'password' => ['required', 'min:6']
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'obrigatório',
            'email.email' => 'inválido',
            'email.min' => 'inválido',
            'email.exists' => 'inválido',
            'password.required'=> 'obrigatório',
            'password.min'=> 'inválido'
        ];
    }
}
