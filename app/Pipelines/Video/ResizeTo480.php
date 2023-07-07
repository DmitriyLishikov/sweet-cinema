<?php

namespace App\Pipelines\Video;

use App\Models\Video;
use Closure;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\Storage;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Filters\Video\ResizeFilter;
class ResizeTo480 implements VideoUploadProcessingPipe
{

    /**
     * @param Video $video
     * @param Closure $next
     * @return mixed
     */
    public function handle(Video $video, Closure $next)
    {
        if(!Storage::disk('public')->exists('archive')) {
            Storage::disk('public')->makeDirectory('archive');
        }

        $pathResizeVideo = $video->settings['convert_path'] ?? $video->settings['tmp_path'];

        Storage::disk('public')->makeDirectory('archive/' . $video->id);

        $pathConvertVideo = storage_path('/app/public/archive/' . $video->id . '/' . $video->title .'_480.mp4');

        $ffmpeg = FFMpeg::create();
        $videoConvert = $ffmpeg->open(storage_path('/app/public/' . $pathResizeVideo));
        $videoConvert->filters()->resize(new Dimension(854, 480), ResizeFilter::RESIZEMODE_INSET, true)->synchronize();
        $videoConvert->save(new X264(), $pathConvertVideo);

        $video->update(['settings->path_size_480' => explode('public/', $pathConvertVideo)[1]]);

        return $next($video);
    }

}
