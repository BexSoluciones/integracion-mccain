<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
    	'App\Console\Commands\VerificarTipoDocumento',
        'App\Console\Commands\VerificarTablas',
        'App\Console\Commands\GuardarInformacion',
        'App\Console\Commands\GenerarPlanos',
        'App\Console\Commands\EnviarPlanos',
        'App\Console\Commands\WebService',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('integracion:verificar-tablas')->cron('0 00,06 * * *');
        // $schedule->command('integracion:guardar-informacion')->cron('0 00,06 * * *');
        // $schedule->command('integracion:generar-planos')->cron('0 00,06 * * *');
        // $schedule->command('integracion:enviar-planos')->cron('0 00,06 * * *');
        // $schedule->command('integracion:webservice')->cron('0 00,06 * * *');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
