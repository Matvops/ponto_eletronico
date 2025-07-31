<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewPasswordRequest extends FormRequest
{

    protected $stopOnFirstFailure = true;

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
            'token' => ['bail', 'required'],
            'email' => ['bail', 'required'],
            'password' => ['bail', 'required', 'min:6'],
            'password_confirmation' => ['bail', 'required', 'same:password'],
        ];
    }

    public function messages()
    {
        return [
            'token.required' => 'token inválido',
            'email.required' => 'email inválido',
            'password.required' => 'obrigatório',
            'password_confirmation.required' => 'obrigatório',
            'password.min' => 'tamanho minímo da senha: :min',
            'password_confirmation.same' => 'As senhas devem coincidir'
        ];
    }
}
