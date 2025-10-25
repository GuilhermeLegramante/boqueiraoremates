<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $events = Event::where('published', 1)
                        ->whereNotNull('banner')
                        ->orderBy('start_date')
                        ->get();

        return view('site.home', compact('events'));
    }
}
