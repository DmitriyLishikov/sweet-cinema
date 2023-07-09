<?php

namespace App\Pipelines\Video;

use App\Models\Video;
use App\Services\FFMpegService;
use Closure;
use FFMpeg\FFMpeg;
use FFMpeg\Coordinate\TimeCode;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
class MakePreview implements VideoUploadProcessingPipe
{

    /**
     * @param Video $video
     * @param Closure $next
     * @return mixed
     */
    public function handle(Video $video, Closure $next)
    {
        $pathPreview = storage_path('/app/public/archive/' . $video->id . '/' . $video->title .'.jpg');

        $videoConvert = FFMpegService::make()->open(storage_path('/app/public/' . $video->settings['path_size_480']));
        $frame = $videoConvert->frame(TimeCode::fromSeconds(1));
        $frame->save($pathPreview);

        $img = Image::make(Storage::disk('public')->path('archive/' . $video->id . '/' . $video->title .'.jpg'));
        $img->resize(854, 480, function ($const) {
            $const->aspectRatio();
        })->save(Storage::disk('public')->path('archive/' . $video->id . '/' . $video->title .'.jpg'));

        $video->update(['settings->preview' => explode('public/', $pathPreview)[1]]);

        return $next($video);
    }

}
