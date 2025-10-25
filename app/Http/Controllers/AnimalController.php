<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Banner;
use App\Models\Event;
use App\Models\AnimalEvent; // <-- importar
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function show(Event $event, Animal $animal)
    {
        $events = Event::where('published', 1)->whereNotNull('banner')->get();

        // Carrega o animal normal (sem depender do pivot automático)
        $animal = Animal::findOrFail($animal->id);

        // Busca o AnimalEvent (registro do "lote") relacionado a este evento + animal
        $animalEvent = AnimalEvent::where('event_id', $event->id)
            ->where('animal_id', $animal->id)
            ->first();

        if (! $animalEvent) {
            abort(404, 'Lote não encontrado para este evento.');
        }

        // Define explicitamente a relação 'pivot' do Animal como o AnimalEvent encontrado.
        // Isso faz com que $animal->pivot->id, min_value, increment_value, etc. estejam disponíveis.
        $animal->setRelation('pivot', $animalEvent);

        // Opcional: você pode setar também a relação 'events' ou outras, se precisar.
        // $animal->setRelation('events', collect([$event]));

        return view('site.animals.show', compact('event', 'animal', 'events'));
    }
}
