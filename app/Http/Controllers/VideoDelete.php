<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteVideoRequest;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;

class VideoDelete extends Controller
{
    public function __invoke(DeleteVideoRequest $request){
        Video::where('id', $request->input('id'))->delete();

        Storage::disk('public')->deleteDirectory('archive/' . $request->input('id'));

        return response()->json(['success' => true]);
    }
}
