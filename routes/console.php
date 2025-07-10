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
// CORREÇÃO: Usando o facade Schedule diretamente e everyFiveMinutes()
Schedule::command(ProcessLaunchCountToValoresMensais::class)->everyFiveMinutes();
Schedule::command(ProcessFluxoCaixaToValoresMensais::class)->everyFiveMinutes();  

// A linha para o worker de fila também deve estar aqui, se você a tinha no Kernel.php
// Se você não a tinha no Kernel.php e quer que o agendador a dispare, adicione:
Schedule::command('queue:work --stop-when-empty')->everyMinute();