<?php

namespace App\Services;

use App\Models\Video;

class GetVideos
{
    public function __invoke(){
        return Video::orderBy('id')
            ->where('settings->upload', true)
            ->paginate(10);
    }
}
