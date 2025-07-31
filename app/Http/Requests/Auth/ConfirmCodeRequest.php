<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ConfirmCodeRequest extends FormRequest
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
            'numberOne' => ['bail', 'required', 'numeric', 'max:9', 'min:1'],
            'numberTwo' => ['bail', 'required', 'numeric', 'max:9', 'min:0'],
            'numberTree' => ['bail', 'required', 'numeric', 'max:9', 'min:0'],
            'numberFour' => ['bail', 'required', 'numeric', 'max:9', 'min:0'],
            'email' => ['bail', 'required', 'email', 'exists:users,email']
        ];
    }

    public function messages()
    {
        return [
            'numberOne.required' => 'obrigatório',
            'numberTwo.required' => 'obrigatório',
            'numberTree.required' => 'obrigatório',
            'numberFour.required' => 'obrigatório',
            'email.required' => 'obrigatório',
            'numberOne.numeric' => 'inválido',
            'numberTwo.numeric' => 'inválido',
            'numberTree.numeric' => 'inválido',
            'numberFour.numeric' => 'inválido',
            'numberOne.max' => 'inválido',
            'numberTwo.max' => 'inválido',
            'numberTree.max' => 'inválido',
            'numberFour.max' => 'inválido',
            'numberOne.min' => 'inválido',
            'numberTwo.min' => 'inválido',
            'numberTree.min' => 'inválido',
            'numberFour.min' => 'inválido',
            'email.email' => 'inválido',
            'email.exists' => 'inválido',
        ];
    }
}
