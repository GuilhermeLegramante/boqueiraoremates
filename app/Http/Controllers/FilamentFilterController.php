<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilamentFilterController extends Controller
{
    public function update(Request $request)
    {
        $resource = $request->input('resource');

        if ($request->has('clear')) {
            session()->forget("{$resource}.selected_event_id");
            session()->forget("{$resource}.selected_lot_id");
            session()->forget("{$resource}.selected_client_id");
            session()->forget("{$resource}.selected_status_id");
            return redirect()->back();
        }

        // Salva sempre como string
        session(["{$resource}.selected_event_id" => (string) $request->input('selected_event_id')]);
        session(["{$resource}.selected_lot_id" => (string) $request->input('selected_lot_id')]);
        session(["{$resource}.selected_client_id" => (string) $request->input('selected_client_id')]);
        session(["{$resource}.selected_status_id" => (string) $request->input('selected_status_id')]);

        return redirect()->back();
    }


    public function lots($eventId)
    {
        $lots = \App\Models\AnimalEvent::where('event_id', $eventId)->pluck('name', 'id');
        return response()->json($lots->map(fn($name, $id) => ['id' => $id, 'name' => $name])->values());
    }
}
