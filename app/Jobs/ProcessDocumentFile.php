<?php

namespace App\Jobs;

use App\Models\Processfile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Jobs\Middleware\JobRateLimiterMiddleware;
use DateTime;
use Illuminate\Queue\Middleware\ThrottlesExceptions;
use Illuminate\Queue\Middleware\WithoutOverlapping;

class ProcessDocumentFile implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $tries = 2;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public $fileContent,
        public $filename,
        public $user_id
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try {
        $fullpath = "laravel-notification/" . $this->filename;
        Storage::disk("s3")->put($fullpath, $this->fileContent);
        Processfile::create([
            "filename"  => $this->filename,
            "path" => $fullpath,
            "user_id" => $this->user_id
        ]);
        } catch (\Throwable $th) {
            $this->fail($th);
        }
    }


    /**
     * 
     */
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping($this->user_id))
                ->shared()
                ->releaseAfter(60),
            new ThrottlesExceptions(10, 5)
        ];
    }


    /**
     * Determine the time at which the job should timeout.
     */
    public function retryUntil(): DateTime
    {
        return now()->addMinutes(1);
    }
}
