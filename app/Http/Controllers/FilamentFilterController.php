<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilamentFilterController extends Controller
{
    // Salvar filtros ou limpar
    public function update(Request $request)
    {
        // Se vier ?clear=1 → limpar filtros
        if ($request->query('clear') == 1) {
            session()->forget('selected_event_id');
            session()->forget('selected_lot_id');
            session()->forget('selected_client_id');
            session()->forget('selected_status_id');

            return redirect()->back();
        }

        // Salvar filtros
        session([
            'selected_event_id'  => $request->input('selected_event_id'),
            'selected_lot_id'    => $request->input('selected_lot_id'),
            'selected_client_id' => $request->input('selected_client_id'),
            'selected_status_id' => $request->input('selected_status_id'),
        ]);

        return redirect()->back();
    }

    public function lots($eventId)
    {
        $lots = \App\Models\AnimalEvent::where('event_id', $eventId)->pluck('name', 'id');
        return response()->json($lots->map(fn($name, $id) => ['id' => $id, 'name' => $name])->values());
    }
}
