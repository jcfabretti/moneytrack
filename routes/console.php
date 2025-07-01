<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule; // Importa o facade Schedule
use App\Console\Commands\ProcessFluxoCaixaToValoresMensais; // Importa a classe do comando
use App\Console\Commands\ProcessLaunchCountToValoresMensais; // Importa a classe do comando

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with your commands' IO methods.
|
*/

// Seu comando de teste simples (mantido)
Artisan::command('teste:simples', function () {
    $this->comment('Este é um comando de teste simples!');
})->purpose('Um comando para testar a descoberta.');

// Seus comandos agendados, agora definidos no routes/console.php
// CORREÇÃO: Usando o facade Schedule diretamente
Schedule::command(ProcessLaunchCountToValoresMensais::class)->dailyAt('09:00');
Schedule::command(ProcessFluxoCaixaToValoresMensais::class)->dailyAt('09:00');

// Ou, se preferir agendar pelo nome da signature (como antes):
// Schedule::command('lancamentos:process-monthly')->everyMinute();
// Schedule::command('fluxocaixa:process-monthly')->everyMinute();

// Exemplo da documentação:
// Schedule::call(function () {
//     DB::table('recent_users')->delete();
// })->daily();

// Schedule::command('emails:send Taylor --force')->daily();
// Schedule::command(SendEmailsCommand::class, ['Taylor', '--force'])->daily();