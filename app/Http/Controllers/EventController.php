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
        // $event->load('animals');
        $event->load(['animals' => function ($query) {
            $query->orderBy('animal_event.lot_number');
        }]);

        return view('site.events.show', compact('event', 'events'));
    }
}
