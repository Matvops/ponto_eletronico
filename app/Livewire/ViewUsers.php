<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ViewUsers extends Component
{

    use WithPagination;

    public $query = '';
    public $number = 2;
    public $filter = 'ASC';


    public function mount()
    {
        if(session()->has('error_delete_user')) {
            $this->dispatch('exibirModal',
                "Erro!",
                "error",
                session('error_delete_user'),
                "center"
            );
        }

        if(session()->has('success_delete_user')) {
            $this->dispatch('exibirModal',
                "Sucesso!",
                "success",
                session('success_delete_user'),
                "center"
            );
        }

        if(session()->has('error_update_user')) {
            $this->dispatch('exibirModal',
                "Erro!",
                "error",
                session('error_update_user'),
                "center"
            );
        }

        if(session()->has('success_update_user')) {
            $this->dispatch('exibirModal',
                "Sucesso!",
                "success",
                session('success_update_user'),
                "center"
            );
        }
    }

    public function asc()
    {
        $this->filter = 'ASC';
    }

    public function desc()
    {
        $this->filter = 'DESC';
    }

    #[Title('Visualizar Usuários')]
    public function render()
    {
        return view('livewire.view-users', ['users' => User::where("username", "like", "%$this->query%")
                                                                ->where('usr_id', '!=', Auth::user()->usr_id)
                                                                ->where('role', '!=', 'ADMIN')
                                                                ->orderBy('username', $this->filter)
                                                                ->paginate($this->number)]);
    }
}
