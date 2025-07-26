<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Title;
use Livewire\Component;

class Login extends Component
{
    #[Title('Login')]
    public function render()
    {
        return view('livewire.auth.login');
    }
}
