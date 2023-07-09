<?php

namespace App\Pipelines\Video;

use App\Models\Video;
use App\Services\FFMpegService;
use Closure;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\Storage;

class ConvertToMP4 implements VideoUploadProcessingPipe
{

    /**
     * @param Video $video
     * @param Closure $next
     * @return mixed
     */
    public function handle(Video $video, Closure $next)
    {
        if(explode('.', $video->settings['tmp_path'])[1] !== 'mp4'){
            if(!Storage::disk('public')->exists('convert')) {
                Storage::disk('public')->makeDirectory('convert');
            }

            $pathConvertVideo = storage_path('/app/public/convert/' . $video->title .'.mp4');
            $videoConvert = FFMpegService::make()->open(storage_path('/app/public/' . $video->settings['tmp_path']));
            $format = new X264();
            $format->setAudioCodec("libmp3lame");
            $videoConvert->save($format, $pathConvertVideo);

            $video->update(['settings->convert_path' => explode('public/', $pathConvertVideo)[1]]);
        }

        return $next($video);
    }

}
