<?php

namespace App\Repositories;

use App\Enums\TimeSheetStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TimeSheetRepository 
{

    public function getTimesBalance($id): ?array
    {
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
                AND A.status = ?
                AND B.status = ?
            ";

            return DB::select($sql, [
                $id ?? Auth::user()->usr_id,
                $id ?? Auth::user()->usr_id,
                TimeSheetStatus::ATIVO->value,
                TimeSheetStatus::ATIVO->value
            ]);
    }
}