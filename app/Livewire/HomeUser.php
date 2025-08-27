<?php

namespace App\Livewire;

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

        
        $this->getDifferenceHours();
    }

    private function getDifferenceHours(){
        $sql = "SELECT 
                CASE
                    WHEN ((B.updated_at) - (A.updated_at + INTERVAL '10 hours')) < INTERVAL '-10 hours' THEN '00:00:00'
                    ELSE (B.updated_at) - (A.updated_at + INTERVAL '10 hours')
                END AS value
                FROM time_sheet A
                LEFT JOIN time_sheet B ON A.date = B.date 
                AND A.tis_usr_id = ?
                AND B.tis_usr_id = ?
                WHERE A.type = 'ENTRADA' 
                AND B.type = 'SAIDA'
                AND A.status = 'ATIVO'
                AND B.status = 'ATIVO'
        ";

        $differences = DB::select($sql, [
            Auth::user()->usr_id,
            Auth::user()->usr_id
        ]);

        $this->calculateHoursIfExists($differences);
    }

    private function calculateHoursIfExists($timesBalances){
        if(!$timesBalances){
            $this->timeBalance = "00:00:00";
            $this->statusTimeBalance = false;
            return;
        }

        $seconds = 0;
        $minutes = 0;
        $hours = 0;
        foreach ($timesBalances as $timeBalance) {
            $difference = $timeBalance->value;
            $seconds = $this->calculate($seconds, $this->extractTimeUnit($difference, 'SECONDS'));
            $minutes = $this->calculate($minutes, $this->extractTimeUnit($difference, 'MINUTES'));
            $hours = $this->calculate($hours, $this->extractTimeUnit($difference, 'HOURS'));
        }

        $this->statusTimeBalance = $this->timeBalanceIsPositive($hours);
        $this->timeBalance = $hours . ':' . $minutes . ':' . $seconds;
    }

    private function extractTimeUnit(string $time, string $unit): int
    {
        $timePartitioned = explode(':', $time);
        switch ($unit) {
            case 'SECONDS':
                return intval($timePartitioned[2]);
                break;
            case 'MINUTES':
                return intval($timePartitioned[1]);
                break;
            case 'HOURS':
                return intval($timePartitioned[0]);
                break;
            default:
                throw new Exception("Unidade de tempo inválida");
        }

        return $time;
    }
    
    private function calculate(int $actual, int $difference): int
    {
        
        return $actual + $difference;
    }

    private function timeBalanceIsPositive($time): bool
    {
        return $time >= 0;
    }

    #[Title('Home')]
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.home-user');
    }
}
