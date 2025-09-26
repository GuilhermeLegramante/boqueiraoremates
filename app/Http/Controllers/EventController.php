<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Event $event)
    {
        $events = Event::where('published', 1)->whereNotNull('banner')->get();

        // já carrega os animais relacionados
        $event->load('animals');
        
        return view('site.events.show', compact('event', 'events'));
    }
}
