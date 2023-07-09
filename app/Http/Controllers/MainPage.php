<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MainPage extends Controller
{

    /**
     * @return \Inertia\Response
     */
    public function __invoke(){
        return Inertia::render('Main');
    }

}
