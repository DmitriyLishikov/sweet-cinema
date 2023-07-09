<?php

namespace App\Services;

use FFMpeg\FFMpeg;

class FFMpegService
{

    /**
     * @return FFMpeg
     */
    public static function make(): FFMpeg
    {
       return FFMpeg::create();
    }

}
