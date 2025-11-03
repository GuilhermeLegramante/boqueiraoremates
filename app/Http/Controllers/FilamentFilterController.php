<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FilamentFilterController extends Controller
{
    public function update(Request $request)
    {
        // Recupera o prefixo enviado pelo formulário ou usa padrão
        $prefix = $request->input('session_prefix', 'default_');

        // Se o parâmetro 'clear' estiver presente, limpa todas as sessões relacionadas
        if ($request->input('clear')) {
            $keys = [
                'selected_event_id',
                'selected_lot_id',
                'selected_client_id',
                'selected_status_id',
            ];

            foreach ($keys as $key) {
                $request->session()->forget($prefix . $key);
            }

            return redirect()->back();
        }

        // Atualiza os filtros na sessão
        $request->session()->put($prefix . 'selected_event_id', $request->input('selected_event_id'));
        $request->session()->put($prefix . 'selected_lot_id', $request->input('selected_lot_id'));
        $request->session()->put($prefix . 'selected_client_id', $request->input('selected_client_id'));
        $request->session()->put($prefix . 'selected_status_id', $request->input('selected_status_id'));

        return redirect()->back();
    }

    public function lots($eventId)
    {
        $lots = \App\Models\AnimalEvent::where('event_id', $eventId)->pluck('name', 'id');
        return response()->json($lots->map(fn($name, $id) => ['id' => $id, 'name' => $name])->values());
    }
}
