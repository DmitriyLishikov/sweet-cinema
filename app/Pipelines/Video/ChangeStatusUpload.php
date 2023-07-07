<?php

namespace App\Pipelines\Video;

use App\Models\Video;
use Closure;
class ChangeStatusUpload implements VideoUploadProcessingPipe
{

    /**
     * @param Video $video
     * @param Closure $next
     * @return mixed
     */
    public function handle(Video $video, Closure $next)
    {
        $video->update(['settings->upload' => true]);

        return $next($video);
    }

}
