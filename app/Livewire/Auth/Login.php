<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;

class Login extends Component
{
    public function mount()
    {
        if(session()->has('success_store_new_password')) {
            $this->dispatch('success_store_new_password', 
                "Sucesso",
                "success",
                "Nova senha cadastrada!",
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
