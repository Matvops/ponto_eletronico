<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class ViewUsers extends Component
{

    use WithPagination;

    public $query = '';
    public $number = 2;
    public $filter = 'ASC';

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
                                                                ->orderBy('username', $this->filter)
                                                                ->paginate($this->number)]);
    }
}
