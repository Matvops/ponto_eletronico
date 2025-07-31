<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

class ConfirmationCode extends Component
{
    #[Url(as: 'email')]
    public string $email;

    #[Title('Confirm code')]
    public function render()
    {
        return view('livewire.confirmation-code');
    }
}
