<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\UserNotificationMessage;
use DateTime;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use PhpParser\Node\Expr\Cast\Array_;

class CreateNewUser
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;



    public $tries = 3;

    public User $user;
    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
      $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
      try {
        UserNotificationMessage::create([
            "user_id" => $this->user->id,
            "message" => fake()->text()
          ]);
      } catch (\Throwable $th) {
           report("SOMETHING HAPPEN BAD");
           $this->fail($th);
      }
    }


    public function middleware() : array
    {
        return [
           ( new WithoutOverlapping($this->user->id))
              ->shared()
              ->expireAfter(180)
            ,
            new ThrottlesExceptions(5,5)
        ];
    }

    public function retryUntil(): DateTime
    {
        return now()->addMinute(1);
    }

}
