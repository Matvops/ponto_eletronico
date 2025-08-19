<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AtualizaFeriadosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:atualiza-feriados-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza feriados, na tabela holidays.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $actualYear = Carbon::now()->format('Y');


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://brasilapi.com.br/api/feriados/v1/$actualYear",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false
        ));

        $response = curl_exec($curl);
        $responseObject = json_decode($response, true);

        curl_close($curl);

        foreach($responseObject as $object)
        {
            DB::table('holidays')->insert(
                [
                    'date' => $object['date'],
                    'description' => $object['name']
                ]
            );
        }
    }
}
