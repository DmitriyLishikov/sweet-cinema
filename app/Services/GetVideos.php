<?php

namespace App\Services;

use App\Models\Video;

class GetVideos
{
    public function __invoke(){
        return Video::orderBy('id')->paginate(10);
    }
}
