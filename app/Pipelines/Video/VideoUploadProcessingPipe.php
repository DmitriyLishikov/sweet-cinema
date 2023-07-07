<?php

namespace App\Pipelines\Video;

use App\Models\Video;
use Closure;

interface VideoUploadProcessingPipe
{
    /**
     * @param Video $video
     * @param Closure $next
     * @return mixed
     */
    public function handle(Video $video, Closure $next);
}
