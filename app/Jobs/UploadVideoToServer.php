<?php

namespace App\Jobs;

use App\Models\Video;
use App\Pipelines\Video\ChangeStatusUpload;
use App\Pipelines\Video\ConvertToMP4;
use App\Pipelines\Video\GetDurationVideo;
use App\Pipelines\Video\GetSizesVideos;
use App\Pipelines\Video\MakePreview;
use App\Pipelines\Video\RemoveTmpFiles;
use App\Pipelines\Video\ResizeTo1080;
use App\Pipelines\Video\ResizeTo480;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UploadVideoToServer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public string $path;

    public string $filename;

    /**
     * Steps of lead processing pipeline
     *
     * @var array|string[]
     */
    protected array $pipes = [
        ConvertToMP4::class,
        ResizeTo480::class,
        ResizeTo1080::class,
        MakePreview::class,
        GetDurationVideo::class,
        GetSizesVideos::class,
        RemoveTmpFiles::class,
        ChangeStatusUpload::class,
    ];

    /**
     * Create a new job instance.
     */
    public function __construct(string $path, string $filename)
    {
        $this->path     = $path;
        $this->filename = explode('.', $filename)[0];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $video = Video::create([
            'title' => $this->filename,
            'settings->tmp_path' => $this->path,
            'settings->upload' => false,
        ]);

        try {
            app(Pipeline::class)
                ->send($video)
                ->through($this->pipes)
                ->thenReturn();
        }catch (Throwable $exception){
            Log::error($exception->getMessage());
            $video->delete();
            Storage::disk('public')->delete($this->path);
        }

    }
}
