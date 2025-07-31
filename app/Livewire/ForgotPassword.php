<?php

namespace App\Livewire;

use App\Services\AuthService;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ForgotPassword extends Component
{
    #[Validate(rule:'required', message:'obrigatório')]
    #[Validate(rule:'email', message:'inválido')]
    #[Validate(rule:'exists:users,email', message:'inválido')]
    public string $email;

    public function mount() {
        $this->email = '';
    }

    public function submit() {
        $this->validate();

        $this->email;

        $service = new AuthService;

        $service->sendEmailConfirmation($this->email);

        return redirect()->route('confirm_code_view', ['email' => $this->email]);
    }

    #[Title('Recuperar senha')]
    public function render()
    {
        return view('livewire.forgot-password');
    }
}
