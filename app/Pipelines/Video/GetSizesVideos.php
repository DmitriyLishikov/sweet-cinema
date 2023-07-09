<?php

namespace App\Pipelines\Video;

use App\Models\Video;
use App\Services\FFProbeService;
use Closure;
use Illuminate\Support\Facades\Storage;
class GetSizesVideos implements VideoUploadProcessingPipe
{

    /**
     * @param Video $video
     * @param Closure $next
     * @return mixed
     */
    public function handle(Video $video, Closure $next)
    {
        $video->update([
            'settings->size_file_480'  => number_format(FFProbeService::make()->format(Storage::disk('public')->path($video->settings['path_size_480']))->get('size') / 1048576, 2) . 'MB',
            'settings->size_file_1080' => number_format(FFProbeService::make()->format(Storage::disk('public')->path($video->settings['path_size_1080']))->get('size') / 1048576, 2) . 'MB',
        ]);

        return $next($video);
    }

}
