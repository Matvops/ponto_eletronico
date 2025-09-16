<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\TimeSheetService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Livewire\Attributes\Title;
use Livewire\Component;

class AdminUpdateProfile extends Component
{
    
    public $usr_id;
    public $timeBalance;
    public $statusTimeBalance;
    public $checked;
    public $username;
    public $email;
    
    public function mount($id) {
        $this->checked = false;

        $user = User::find(Crypt::decrypt($id));

        $this->username = $user->username;
        $this->email = $user->email;

        $this->getTimeBalance($id);
    }


    private function getTimeBalance($id) {

        $this->usr_id = Crypt::decrypt($id);

        $service = app(TimeSheetService::class);
        $response = $service->calculateTimeBalance($this->usr_id);

        if(!$response->getStatus()) {
            Auth::logout();
            return redirect('login')->with('error_calculate_time_balance', $response->getMessage());
        }

        $dados = $response->getData();

        $this->timeBalance = $dados['timeBalance'];
        $this->statusTimeBalance = $dados['status'];
    }

    public function alterStateCheckBox() {
        if($this->checked) $this->checked = 0;
        else $this->checked = true;
    }

    #[Title('Atualizar dados')]
    public function render()
    {
        return view('livewire.admin-update-profile');
    }
}
