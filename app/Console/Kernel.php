<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
// use App\Console\Commands\ProcessFluxoCaixaToValoresMensais; // Descomente se precisar referenciar diretamente
// use App\Console\Commands\ProcessLaunchCountToValoresMensais; // Descomente se precisar referenciar diretamente

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Seus comandos personalizados são listados aqui para que o Artisan os reconheça.
        // Eles não são agendados automaticamente por estarem aqui.
        \App\Console\Commands\ProcessFluxoCaixaToValoresMensais::class,
        \App\Console\Commands\ProcessLaunchCountToValoresMensais::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Adicione aqui os comandos que você viu no 'php artisan schedule:list'
        // e o comando para o worker de fila.

        // Exemplo dos seus comandos agendados (agora rodam a cada 5 minutos)
        $schedule->command('lancamentos:process-monthly')->everyFiveMinutes(); // Alterado para everyFiveMinutes()
        $schedule->command('fluxocaixa:process-monthly')->everyFiveMinutes();  // Alterado para everyFiveMinutes()

        // Esta é a linha CRUCIAL para o worker de fila na Hostinger
        $schedule->command('queue:work --stop-when-empty')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands'); // Esta linha carrega os comandos da pasta Commands
                                         // e é o motivo pelo qual seus comandos personalizados
                                         // são reconhecidos pelo Artisan.

        require base_path('routes/console.php');
    }
}