<?php

namespace App\Services;

use App\Repositories\TimeSheetRepository;
use App\Utils\Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Throwable;

class TimeSheetService {

    private TimeSheetRepository $timeSheetRepository;

    public function __construct(TimeSheetRepository $timeSheetRepository)
    {
        $this->timeSheetRepository = $timeSheetRepository;
    }


    public function punchClock($tis_id): Response
    {
        try {
            
            DB::beginTransaction();

            $tis_id = Crypt::decrypt($tis_id);
            
            $clock = $this->timeSheetRepository->getTimeSheetByTisId($tis_id);
            
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
           
            $differences = $this->timeSheetRepository->getTimesBalance($id);

            $dados = $this->calculateHoursIfExists($differences);
            
            return Response::getResponse(true, data: $dados);
        } catch(Throwable $e) {
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
    
        $totalHours = 0;
        foreach($timesBalances as $timeBalance) {
            $totalHours += $this->timeToSeconds($timeBalance->value);
        }

        return [
            'timeBalance' => $this->secondsToTime($totalHours),
            'status' => $this->timeBalanceIsPositive($totalHours)
        ];
    }

    
    private function timeToSeconds($time): int
    {
        $negative = false;

        $parts = explode(':', $time);

        if($time[0] == '-') {
            $negative = true;
        }

        return $negative ? ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2] * -1 : ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
    }

    private function secondsToTime($seconds): string
    {
        $hours = floor($seconds / 3600);
        $seconds = $seconds % 3600;
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, abs($minutes), abs($seconds));
    }

    private function timeBalanceIsPositive($time): bool
    {
        return $time >= 0;
    }
}