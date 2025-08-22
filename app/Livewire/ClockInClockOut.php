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

    public function mount()
    {
        /* $this->last_clock = TimeSheet::where('tis_usr_id', Auth::user()->id)->where('') */

        $sql = "SELECT TO_CHAR(updated_at, 'DD/MM/YYYY HH24:MI:SS') AS updated_at, A.type FROM time_sheet AS A
                        WHERE tis_usr_id = ?
                        AND TO_CHAR(updated_at, 'HH24:MI:SS') != '00:00:00'
                        ORDER BY A.updated_at DESC
                        limit 1"; 
        
        $last_clock = DB::select($sql, [Auth::user()->usr_id]);

        $this->last_clock_time = $last_clock[0]->updated_at; 
        $this->last_type_clock = $last_clock[0]->type; 

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

        $this->status = $actual_clock[0]->status;
        $this->actual_type_clock = strtolower($actual_clock[0]->type); 
    }

    public function render()
    {
        return view('livewire.clock-in-clock-out');
    }
}
