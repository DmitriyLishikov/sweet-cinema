<?php

namespace App\Actions;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVideoRequest;
use App\Jobs\UploadVideoToServer;

class UploadVideo extends Controller
{
    public function __invoke(StoreVideoRequest $request): \Illuminate\Http\JsonResponse
    {
        UploadVideoToServer::dispatch($request->file('file')->store('tmp'));
        return response()->json(['success' => true]);
    }
}
