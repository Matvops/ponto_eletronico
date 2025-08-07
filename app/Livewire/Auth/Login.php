<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;

class Login extends Component
{
    public function mount()
    {
        if(session()->has('success_store_new_password')) {
            $this->dispatch('exibirModal', 
                "Sucesso",
                "success",
                "Nova senha cadastrada!",
                "center"
            );
        }

        if(session()->has('success_update_profile')) {
            $this->dispatch('exibirModal', 
                "Sucesso",
                "success",
                session('success_update_profile'),
                "center"
            );
        }
    }

    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
