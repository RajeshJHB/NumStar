<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class InfoController extends Controller
{
    public function about(): View
    {
        return view('info.about');
    }

    public function help(): View
    {
        return view('info.help');
    }
}



