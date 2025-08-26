<?php

namespace App\Livewire;

use App\Models\TimeSheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

class ViewDays extends Component
{
    public $query = '';
    public $order = 'DESC';
    public $filter = 'time_sheet_date';

    public function desc()
    {
        $this->order = 'DESC';
    }


    public function asc()
    {
        $this->order = 'ASC';
    }

    public function positivo()
    {
        $this->filter = 'status';
        $this->order = 'DESC';
    }

    public function negativo()
    {
        $this->filter = 'status';
        $this->order = 'ASC';
    }

    public function maisPositivo()
    {
        $this->filter = 'status';
        $this->order = 'DESC';
    }

    public function maisNegativo()
    {
        $this->filter = 'difference';
        $this->order = 'ASC';
    }

    #[Title('Visualizar dias')]
    public function render()
    {
     
        $sql = "
                SELECT TO_CHAR(A.date, 'DD/MM/YYYY') as time_sheet_date, TO_CHAR(A.updated_at, 'HH24:MI:SS') AS entry,
                TO_CHAR(B.updated_at, 'HH24:MI:SS') AS output,
                CASE
                    WHEN ((B.updated_at) - (A.updated_at + INTERVAL '10 hours')) < INTERVAL '-10 hours' THEN '00:00:00'
                    ELSE (B.updated_at) - (A.updated_at + INTERVAL '10 hours')
                END AS difference,
                CASE
                    WHEN A.updated_at + INTERVAL '10 hours' > B.updated_at THEN 'NEGATIVO' 
                    ELSE 'POSITIVO'
                END AS status
                FROM time_sheet A
                LEFT JOIN time_sheet B ON A.date = B.date 
                AND A.tis_usr_id = ?
                AND B.tis_usr_id = ?
                WHERE A.type = 'ENTRADA' 
                AND B.type = 'SAIDA'
                AND TO_CHAR(A.date, 'DD/MM/YYYY') like '%$this->query%'        
                ORDER BY $this->filter $this->order
        ";
        

        $response = DB::select($sql, [
            Auth::user()->usr_id,
            Auth::user()->usr_id
        ]);

        return view('livewire.view-days', ['time_sheets' => $response]);
    }
}
