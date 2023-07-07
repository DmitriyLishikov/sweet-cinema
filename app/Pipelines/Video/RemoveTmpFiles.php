<?php

namespace App\Pipelines\Video;

use App\Models\Video;
use Closure;
use Illuminate\Support\Facades\Storage;
class RemoveTmpFiles implements VideoUploadProcessingPipe
{

    /**
     * @param Video $video
     * @param Closure $next
     * @return mixed
     */
    public function handle(Video $video, Closure $next)
    {
        Storage::disk('public')->delete($video->settings['tmp_path']);

        if(isset($video->settings['convert_path'])){
            Storage::disk('public')->delete($video->settings['convert_path']);
        }

        return $next($video);
    }

}
