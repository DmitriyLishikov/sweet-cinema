<?php

namespace App\Jobs;

use http\Env\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Throwable;

class UploadVideoToServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public string $path;

    /**
     * Create a new job instance.
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        Log::info(print_r($path, true));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
//        Bus::chain([
//
//        ])->catch(function (Throwable $e) {
//            // A job within the chain has failed...
//        })->dispatch();
    }
}
