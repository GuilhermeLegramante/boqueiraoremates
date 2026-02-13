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
        // $event->load(['animals' => function ($query) {
        //     $query->orderBy('animal_event.lot_number');
        // }]);

        // Para resolver o problema de 01.10 vir antes de 01.09
        $event->load(['animals' => function ($query) {
            $query->orderByRaw("
        CAST(SUBSTRING_INDEX(animal_event.lot_number, '.', 1) AS UNSIGNED),
        CAST(SUBSTRING_INDEX(animal_event.lot_number, '.', -1) AS UNSIGNED)
    ");
        }]);

        return view('site.events.show', compact('event', 'events'));
    }
}
