<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalEvent;
use App\Models\Event;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function show(Event $event, AnimalEvent $animalEvent)
    {
        $events = Event::where('published', 1)->whereNotNull('banner')->get();

        // Garante que o lote realmente pertence ao evento acessado
        if ($animalEvent->event_id !== $event->id) {
            abort(404, 'Lote não pertence a este evento.');
        }

        // Carrega o animal relacionado
        $animal = $animalEvent->animal;

        // Injeta o pivot no animal, para manter compatibilidade com as views existentes
        $animal->setRelation('pivot', $animalEvent);

        dd($animal);

        return view('site.animals.show', compact('event', 'animal', 'events'));
    }
}
