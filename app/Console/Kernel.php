<?php

namespace App\Console;

use App\Http\Controllers\LoggingController;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Activity::where('created_at', '<', Carbon::now()->addDays(-21))->delete();
            Activity::create([
                'log_name' => 'Удаление лога',
                'description' => 'deleted',
                'subject_type' => 'App\Models\User',
                'event' => null,
                'subject_id' => 62,
                'causer_type' => 'App\Models\User',
                'causer_id' => auth('sanctum')->user()->id,
                'properties' => null,
                'batch_uuid' => null,
            ]);
        })->daily();
        // $schedule->command('inspire')->hourly();
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
