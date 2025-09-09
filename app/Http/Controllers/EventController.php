<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function show(Event $event)
    {
        // já carrega os animais relacionados
        $event->load('animals');

        return view('site.events.show', compact('event'));
    }
}
