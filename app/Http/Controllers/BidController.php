<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bid;
use App\Models\Event;
use App\Services\NotificationService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BidController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function store(Request $request)
    {
        $request->validate([
            'animal_event_id' => 'required|exists:animal_event,id',
            'event_id' => 'required|exists:events,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $userId = Auth::id();

        // 🔒 Verifica se já existe lance com o mesmo valor para o mesmo animal/evento
        $existingBid = Bid::where('animal_event_id', $request->animal_event_id)
            ->where('event_id', $request->event_id)
            ->where('amount', $request->amount)
            ->where('status', '!=', 2) // Ignora lances rejeitados
            ->first();

        if ($existingBid) {
            return back()->with('error', 'Já existe um lance com este mesmo valor para este lote.');
        }

        // ✅ Cria o novo lance
        Bid::create([
            'animal_event_id' => $request->animal_event_id,
            'event_id' => $request->event_id,
            'user_id' => $userId,
            'amount' => $request->amount,
            'status' => false,
        ]);

        // Busca dados para notificação
        $user = Auth::user();
        $event = Event::find($request->event_id);
        $animal = DB::table('animal_event')
            ->join('animals', 'animals.id', '=', 'animal_event.animal_id')
            ->where('animal_event.id', $request->animal_event_id)
            ->select('animals.name', 'animal_event.lot_number')
            ->first();

        // 3. Chama o serviço de notificação
        $this->notificationService->sendNewBidNotifications($user, $event, $animal, $request->amount);

        // ✅ Continua normalmente mesmo se notificações falharem
        return redirect()->back()->with('bid_success', true);
    }

    public function approve(Bid $bid)
    {
        $bid->update([
            'status' => 1,
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Lance aprovado!');
    }

    public function reject(Bid $bid)
    {
        $bid->update([
            'status' => 2,
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Lance rejeitado!');
    }
}
