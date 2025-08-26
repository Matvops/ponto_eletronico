<?php

namespace App\Livewire;

use App\Models\TimeSheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ClockInClockOut extends Component
{

    public $last_clock_time;
    public $last_type_clock;
    public $status;
    public $actual_type_clock;
    public $id;

    public function mount()
    {
        $lastClock = $this->getLastClock();
        $this->last_clock_time = $lastClock->updated_at; 
        $this->last_type_clock = $lastClock->type; 

        $actualClock = $this->getActualClock();
        $this->status = $actualClock->status;
        $this->actual_type_clock = strtolower($actualClock->type); 
        $this->id = $actualClock->tis_id;

        if(session()->has('error_punch_clock')) {
            $this->dispatch('exibirModal',
                "Erro!",
                "error",
                session('error_punch_clock'),
                "center"
            );
        }

        if(session()->has('success_punch_clock')) {
            $this->dispatch('exibirModal',
                "Sucesso!",
                "success",
                session('success_punch_clock'),
                "center"
            );
        }
    }

    private function getLastClock()
    {
         $sql = "SELECT TO_CHAR(updated_at, 'DD/MM/YYYY HH24:MI:SS') AS updated_at, A.type FROM time_sheet AS A
                        WHERE tis_usr_id = ?
                        AND TO_CHAR(updated_at, 'HH24:MI:SS') != '00:00:00'
                        ORDER BY A.updated_at DESC
                        limit 1"; 
        
        $last_clock = DB::select($sql, [Auth::user()->usr_id]);

        if(!$last_clock) {
            $sql= "SELECT TO_CHAR(updated_at, 'DD/MM/YYYY HH24:MI:SS') AS updated_at, A.type FROM time_sheet AS A
                    WHERE tis_usr_id = ?
                    ORDER BY date, type DESC
                    limit 1";

            $last_clock = DB::select($sql, [Auth::user()->usr_id]);
        }

        return $last_clock[0];
    }

    private function getActualClock()
    {
        $sql = "SELECT tis_id, A.type, updated_at,
                    CASE 
                        WHEN type = 'ENTRADA' AND (EXTRACT(HOUR FROM NOW()) > 8 OR EXTRACT(HOUR FROM NOW()) = 00) THEN 'atrasado'
                        ELSE null
                    END AS status
                FROM time_sheet AS A
                WHERE tis_usr_id = ?
                AND TO_CHAR(date, 'YYYY-MM-DD') = TO_CHAR(NOW(), 'YYYY-MM-DD')
                AND TO_CHAR(updated_at, 'HH24:MI:SS') = '00:00:00'
                ORDER BY A.updated_at DESC, type asc
                limit 1";

        $actual_clock = DB::select($sql, [Auth::user()->usr_id]);
        
        

        return $actual_clock[0] ?? $this->getFakeClock();
    }

    private function getFakeClock()
    {
        return new class {
            public $status = null;
            public $type = 'entrada';
            public $tis_id = 0;
        };
    }


    public function render()
    {
        return view('livewire.clock-in-clock-out');
    }
}
