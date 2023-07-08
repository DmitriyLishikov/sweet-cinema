<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;

class IndexVideos
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function __invoke(Request $request){
        return Video::orderBy('id')
            ->where('settings->upload', true)
            ->cursorPaginate($request->page);
    }
}
