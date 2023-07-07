<?php

namespace App\Pipelines\Video;

use App\Models\Video;
use Closure;
use FFMpeg\FFProbe;
use Illuminate\Support\Facades\Storage;
class GetSizesVideos implements VideoUploadProcessingPipe
{


    public function handle(Video $video, Closure $next)
    {
        $ffprobe = FFProbe::create([
            'ffmpeg.binaries' => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
        ]);

        $size_file_480 = number_format($ffprobe->format(Storage::disk('public')->path($video->settings['path_size_480']))->get('size') / 1048576, 2) . 'MB';
        $size_file_1080 = number_format($ffprobe->format(Storage::disk('public')->path($video->settings['path_size_1080']))->get('size') / 1048576, 2) . 'MB';

        $video->update([
            'settings->size_file_480'  => $size_file_480,
            'settings->size_file_1080' => $size_file_1080,
        ]);

        return $next($video);
    }

}
