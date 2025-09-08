<?php

namespace App\Services;

use App\Enums\TimeSheetStatus;
use App\Models\TimeSheet;
use App\Repositories\TimeSheetRepository;
use App\Utils\Response;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

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

    public static function updateTimeSheetStatus(int $usr_id){

        TimeSheet::where('tis_usr_id', $usr_id)
                    ->where('status', TimeSheetStatus::ATIVO)
                    ->update(['status' => TimeSheetStatus::INATIVO]);
    }
}