<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class HomeUser extends Component
{
    
    public function mount(){
        if(session()->has('success_update_profile')) {
            $this->dispatch('exibirModal',
                "Sucesso!",
                "success",
                session('success_update_profile'),
                "center"
            );
        }

        if(session()->has('success_register_user')) {
            $this->dispatch('exibirModal',
                "Sucesso!",
                "success",
                session('success_register_user'),
                "center"
            );
        }
    }


    #[Title('Home')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.home-user');
    }
}
