<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilamentFilterController extends Controller
{
    public function update(Request $request)
    {
        $resource = $request->input('resource');
        $clear = $request->query('clear', false);

        if ($clear) {
            session()->forget("{$resource}.selected_event_id");
            session()->forget("{$resource}.selected_lot_id");
            session()->forget("{$resource}.selected_client_id");
            session()->forget("{$resource}.selected_status_id");
            return redirect()->back();
        }

        session(["{$resource}.selected_event_id" => $request->input('selected_event_id')]);
        session(["{$resource}.selected_lot_id" => $request->input('selected_lot_id')]);
        session(["{$resource}.selected_client_id" => $request->input('selected_client_id')]);
        session(["{$resource}.selected_status_id" => $request->input('selected_status_id')]);

        return redirect()->back();
    }

    public function lots($eventId)
    {
        $lots = \App\Models\AnimalEvent::where('event_id', $eventId)->pluck('name', 'id');
        return response()->json($lots->map(fn($name, $id) => ['id' => $id, 'name' => $name])->values());
    }
}
