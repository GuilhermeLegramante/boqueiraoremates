<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Banner;
use App\Models\Event;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function show(Event $event, Animal $animal)
    {
        $banners = Banner::where('visible', true)->get();

        // Carrega o animal do evento com a pivot
        $animal = $event->animals()
            ->where('animals.id', $animal->id)
            ->withPivot(['id', 'lot_number', 'min_value', 'increment_value', 'target_value', 'status'])
            ->firstOrFail();

        return view('site.animals.show', compact('event', 'animal', 'banners'));
    }
}
