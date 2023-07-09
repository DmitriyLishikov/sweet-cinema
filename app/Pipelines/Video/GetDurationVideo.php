<?php

namespace App\Pipelines\Video;

use App\Models\Video;
use App\Services\FFProbeService;
use Closure;
use Illuminate\Support\Facades\Storage;
class GetDurationVideo implements VideoUploadProcessingPipe
{

    /**
     * @param Video $video
     * @param Closure $next
     * @return mixed
     */
    public function handle(Video $video, Closure $next)
    {
        $video->update([
            'settings->duration' => FFProbeService::make()
                ->format(Storage::disk('public')
                ->path($video->settings['path_size_480']))
                ->get('duration')
        ]);

        return $next($video);
    }

}
