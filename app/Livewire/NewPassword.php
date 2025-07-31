<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

class NewPassword extends Component
{

    #[Url(as: 'token')]
    public $token;

    #[Url(as: 'email')]
    public $email;

    #[Title('Nova senha')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.new-password');
    }
}
