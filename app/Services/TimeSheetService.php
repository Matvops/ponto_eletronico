<?php

namespace App\Services;

use App\Models\TimeSheet;
use App\Utils\Response;
use Carbon\Carbon;
use Exception;
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
}