<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class RegisterUser extends Component
{

    public function mount() {

        if(session()->has('error_register_user')) {
            $this->dispatch('exibirModal',
                "Erro!",
                "error",
                session('error_register_user'),
                "center"
            );
        }
    }

    #[Title('Criar usuário')]
    public function render()
    {
        return view('livewire.register-user');
    }
}
