<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use  App\Jobs\NotificationDocumentConfirmation;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function(){
                User::create([
                    'name' => fake()->name(),
                    'email' => fake()->unique()->safeEmail(),
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'remember_token' => Str::random(10),
                ]);
        })
          ->onFailure(function($e){
          report($e);
          })
          ->emailOutputTo("mrwilbroadmarktestemail.com")
          ->everyMinute();
        
        $user = User::inRandomOrder()->first();
        $schedule->job(new NotificationDocumentConfirmation($user))
                      ->everyMinute()
                      ->onFailure(function($e){
                      report($e);
                      });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
