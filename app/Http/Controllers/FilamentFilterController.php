<?php

namespace App\Http\Controllers;

use App\Models\AnimalEvent;
use Illuminate\Http\Request;

class FilamentFilterController extends Controller
{
    /**
     * Atualiza os filtros na sessão.
     */
    public function update(Request $request)
    {
        // Se for uma requisição para limpar filtros
        if ($request->has('clear') && $request->query('clear')) {
            $resource = $request->query('clear');
            $namespace = "App\\Filament\\Resources\\{$resource}";
            session()->forget([
                "{$namespace}.selected_event_id",
                "{$namespace}.selected_lot_id",
                "{$namespace}.selected_client_id",
                "{$namespace}.selected_status_id",
            ]);
            return redirect()->back();
        }

        // Recurso (ex: ApprovedActiveBidResource)
        $resource = $request->input('resource');
        $namespace = "App\\Filament\\Resources\\{$resource}";

        // Armazena cada filtro separadamente
        $filters = [
            'selected_event_id' => $request->input('selected_event_id'),
            'selected_lot_id' => $request->input('selected_lot_id'),
            'selected_client_id' => $request->input('selected_client_id'),
            'selected_status_id' => $request->input('selected_status_id'),
        ];

        foreach ($filters as $key => $value) {
            if ($value) {
                session()->put("{$namespace}.{$key}", $value);
            } else {
                session()->forget("{$namespace}.{$key}");
            }
        }

        return redirect()->back();
    }

    /**
     * Retorna os lotes de um evento (usado pelo fetch() do filtro).
     */
    public function lots($eventId)
    {
        $lots = AnimalEvent::query()
            ->where('event_id', $eventId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return response()->json($lots);
    }
}
