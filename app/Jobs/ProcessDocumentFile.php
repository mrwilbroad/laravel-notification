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

class ProcessDocumentFile implements ShouldQueue,ShouldBeEncrypted
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public $file,
        public $filename,
        public $user_id
    ) {
        $this->file = base64_decode($file);
        $this->filename = base64_decode($filename);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        Storage::disk("s3")->putFileAs(
            "laravel-notification",
            $this->file,
            $this->filename
        );
        Processfile::create([
            "filename"  => $this->filename,
            "path" => "laravel-notification/" . $this->filename,
            "user_id" => $this->user_id
        ]);
    }
}
