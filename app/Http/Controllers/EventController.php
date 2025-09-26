<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Event $event)
    {
        // $banners = Banner::where('visible', true)->get();
        $events = Event::where('published')->whereNotNull('banner')->get();

        // já carrega os animais relacionados
        $event->load('animals');
        
        // dd($event->animals);

        return view('site.events.show', compact('event', 'events'));
    }
}
