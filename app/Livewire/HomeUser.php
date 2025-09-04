<?php

namespace App\Livewire;

use App\Http\Controllers\AuthController;
use App\Services\TimeSheetService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;


class HomeUser extends Component
{
 
    public $statusTimeBalance;
    public $timeBalance;

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

        $service = app(TimeSheetService::class);
        $response = $service->calculateTimeBalance(); 

        if(!$response->getStatus()) {
            Auth::logout();
            return redirect('login')->with('error_calculate_time_balance', $response->getMessage());
        }

        $dados = $response->getData();

        $this->timeBalance = $dados['timeBalance'];
        $this->statusTimeBalance = $dados['status'];
    }

    #[Title('Home')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.home-user');
    }
}
