<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

class UpdateProfile extends Component
{
    public $username;
    public $email;

    public function mount() {
        $this->username = Auth::user()->username;
        $this->email = Auth::user()->email;

        if(session()->has('error_update_profile')) {
            $this->dispatch('exibirModal',
                "Erro!",
                "error",
                session('error_update_profile'),
                "center"
            );
        }
    }

    #[Title('Atualizar dados')]
    public function render()
    {
        return view('livewire.update-profile');
    }
}
