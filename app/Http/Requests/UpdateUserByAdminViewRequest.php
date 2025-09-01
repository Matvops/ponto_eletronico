<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserByAdminViewRequest extends FormRequest
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
            'email' => 'required|email',
            'id' => 'required|exists:users,usr_id',
            'reset_time_balance' => 'required',
        ];
    }

    public function message(): array 
    {
        return [
            'username.required' => 'obrigatório',
            'email.email' => 'inválido',
            'email.required' => 'obrigatório',
            'reset_time_balance.required' => 'obrigatório',
            'id.required' => 'usuário inválido',
            'id.exists' => 'usuário inválido',
        ];
    }
}
