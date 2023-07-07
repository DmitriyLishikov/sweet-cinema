<?php

namespace App\Pipelines\Video;

use App\Models\Video;
use Closure;
use FFMpeg\FFProbe;
use Illuminate\Support\Facades\Storage;
class GetDurationVideo implements VideoUploadProcessingPipe
{


    public function handle(Video $video, Closure $next)
    {
        $ffprobe = FFProbe::create([
            'ffmpeg.binaries' => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
        ]);

        $video->update(['settings->duration' => $ffprobe->format(Storage::disk('public')->path($video->settings['path_size_480']))->get('duration')]);

        return $next($video);
    }

}
