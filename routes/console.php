<?php

use App\Console\Commands\AtualizaFeriadosCommand;
use App\Console\Commands\CriarPontosCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command(AtualizaFeriadosCommand::class)->everyMinute(); // PRODUCTION -- YEARLY
Schedule::command(CriarPontosCommand::class)->dailyAt('00:01'); 
