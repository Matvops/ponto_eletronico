<?php

namespace App\Services;

use App\Enums\TimeSheetStatus;
use App\Models\TimeSheet;
use App\Utils\Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class TimeSheetService {


    public function punchClock($tis_id): Response
    {
        try {
            
            DB::beginTransaction();

            $tis_id = Crypt::decrypt($tis_id);

            $clock = TimeSheet::where('tis_id', $tis_id)->first();
            
            if(!$clock) throw new Exception("Erro ao salvar registro de ponto");

            $clock->updated_at = Carbon::now();
            $clock->save();

            DB::commit();
            return Response::getResponse(true, 'Ponto atualizado');
        } catch (Exception $e) {
            DB::rollBack();
            return Response::getResponse(false, $e->getMessage());
        }
    }


    public function calculateTimeBalance($id = null): Response
    {
        try {
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

            $differences = DB::select($sql, [
                $id ?? Auth::user()->usr_id,
                $id ?? Auth::user()->usr_id,
                TimeSheetStatus::ATIVO->value,
                TimeSheetStatus::ATIVO->value
            ]);

            $dados = $this->calculateHoursIfExists($differences);
            
            return Response::getResponse(true, data: $dados);
        } catch(Exception) {
            return Response::getResponse(false, "Erro ao consultar saldo de horas! Entre em contato com o suporte.");
        }
    }


    private function calculateHoursIfExists($timesBalances): array
    {
        if(!$timesBalances){
            return [
                'timeBalance' => "00:00:00",
                'status' => false
            ];
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

        $seconds = $this->formatTime($seconds);
        $minutes = $this->formatTime($minutes);
        $hours = $this->formatTime($hours);

        return [
            'timeBalance' => $hours . ':' . $minutes . ':' . $seconds,
            'status' => $this->timeBalanceIsPositive($hours)
        ];
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

    private function formatTime($time): string
    {
        return $time == 0 ? '00' : strval($time) ; 
    }

    private function timeBalanceIsPositive($time): bool
    {
        return $time >= 0;
    }
}