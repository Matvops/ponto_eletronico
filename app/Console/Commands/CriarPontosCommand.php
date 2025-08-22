<?php

namespace App\Console\Commands;

use App\Models\Holiday;
use App\Models\TimeSheet;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CriarPontosCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:criar-pontos-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria pontos todos os dias para usuários';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $holiday = Holiday::where('date', Carbon::now()->format('Y-m-d'))->first();

        if(Carbon::now()->isWeekday() && !$holiday) {
            $users = User::all();

            foreach ($users as $user) {
                $this->createTimeSheet('ENTRADA', $user->usr_id);    
                $this->createTimeSheet('SAIDA', $user->usr_id);    
            }
        }
    }

    private function createTimeSheet($type, $usr_id) {
                $entry = new TimeSheet();
                $entry->tis_usr_id = $usr_id;    
                $entry->date = Carbon::now()->format('Y-m-d');    
                $entry->type = $type;    
                $entry->updated_at = Carbon::now()->format('Y-m-d 00:00:00');   
                $entry->save();
    }
}
