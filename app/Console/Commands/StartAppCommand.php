<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class StartAppCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inicia o servidor dev e o scheduler:work em paralelo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $this->info('Iniciando aplicação...');


         
        $serve = new Process(['php', 'artisan', 'serve']);
        $serve->setTty(true);
        $serve->start();

        $this->info('Iniciou serve...');


        $schedule = new Process(['php', 'artisan', 'schedule:work']);
        $schedule->setTty(true);
        $schedule->start();

        $this->info('Iniciou schedule...');

        foreach ([$serve, $schedule] as $process) {
            while ($process->isRunning()) {
                usleep(50000);
            }
        }
    }
}
