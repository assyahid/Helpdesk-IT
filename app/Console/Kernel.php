<?php

namespace App\Console;

use App\Console\Commands\DeleteLooseUploads;
use App\Console\Commands\RefreshDemoSite;
use App\Services\Triggers\TriggersCycle;
use Common\Generators\Action\GenerateAction;
use Common\Generators\Controller\GenerateController;
use Common\Generators\Model\GenerateModel;
use Common\Generators\Policy\GeneratePolicy;
use Common\Generators\Request\GenerateRequest;
use Common\Settings\Mail\GmailClient;
use Common\Settings\Settings;
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
//        GenerateController::class,
//        GenerateModel::class,
//        GeneratePolicy::class,
//        GenerateRequest::class,
//        GenerateAction::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(DeleteLooseUploads::class)->daily();

        if (app(Settings::class)->get('mail.handler') === 'gmailApi') {
            $schedule
                ->call(function () {
                    app(GmailClient::class)->watch();
                })
                ->daily();
        }

        $schedule
            ->call(function () {
                app(TriggersCycle::class)->executeTimeBasedTriggers();
            })
            ->hourly();

        if (config('common.site.demo')) {
            $schedule->command(RefreshDemoSite::class)->daily();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
