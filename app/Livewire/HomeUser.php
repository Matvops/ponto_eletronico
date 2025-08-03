<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class HomeUser extends Component
{

    #[Title('Home')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.home-user');
    }
}
