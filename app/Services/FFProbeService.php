<?php

namespace App\Services;

use FFMpeg\FFProbe;

class FFProbeService
{

    /**
     * @return FFProbe
     */
    public static function make(): FFProbe
    {
       return FFProbe::create([
            'ffmpeg.binaries' => env("FFMPEG_BINARIES", '/usr/bin/ffmpeg'),
            'ffprobe.binaries' => env("FFPROBE_BINARIES", '/usr/bin/ffprobe'),
        ]);
    }

}
