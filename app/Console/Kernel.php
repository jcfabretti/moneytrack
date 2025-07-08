<?php


namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ProcessFluxoCaixaToValoresMensais; // Importa a classe do comando de Fluxo de Caixa
use App\Console\Commands\ProcessLaunchCountToValoresMensais; // Importa a classe do comando de Lançamentos
use Illuminate\Contracts\Foundation\Application; // Importa a interface Application
use Illuminate\Events\Dispatcher; // Importa a classe Dispatcher
use Illuminate\Container\Container; // Importa a classe Container se não estiver já no namespace global

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ProcessFluxoCaixaToValoresMensais::class,
        ProcessLaunchCountToValoresMensais::class,
    ];

    // Construtor removido ou sem dd()
    // public function __construct(Application $app, Dispatcher $events)
    // {
    //     dd('Construtor do Kernel foi executado!'); // Linha de depuração
    //     parent::__construct($app, $events);
    // }

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
  
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        // Ocultado o dd() anterior
        // $this->load(__DIR__.'/Commands'); // Pode ser comentado ou removido, pois os comandos estão em $commands
        require base_path('routes/console.php');
    }
}