<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bid;
use App\Models\Event;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BidController extends Controller
{
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'animal_event_id' => 'required|exists:animal_event,id',
    //         'event_id' => 'required|exists:events,id',
    //         'amount' => 'required|numeric|min:0.01',
    //     ]);

    //     Bid::create([
    //         'animal_event_id' => $request->animal_event_id,
    //         'event_id' => $request->event_id,
    //         'user_id' => Auth::id(),
    //         'amount' => $request->amount,
    //         'status' => false,
    //     ]);

    //     // Busca os dados relacionados
    //     $user   = Auth::user();
    //     $event  = Event::find($request->event_id);

    //     // Busca o nome do animal a partir da pivot
    //     $animal = DB::table('animal_event')
    //         ->join('animals', 'animals.id', '=', 'animal_event.animal_id')
    //         ->where('animal_event.id', $request->animal_event_id)
    //         ->select('animals.name')
    //         ->first();

    //     // Monta a mensagem personalizada
    //     $mensagem = "Usuário: {$user->name}\n\n"
    //         . "Evento: {$event->name}\n\n"
    //         . "Animal: {$animal->name}\n\n"
    //         . "Valor do Lance: R$ " . number_format($request->amount, 2, ',', '.') . "\n\n"
    //         . "Lance enviado com sucesso, aguarde validação pela mesa, conforme regulamento do evento.";


    //     Http::withToken('esFDkhJ0D2G0M07nG5K9qCSbQDNC2xUQ5x8IxqHdJYYKHWUi6CxfxbIMfgiq')
    //         ->post('https://www.avisaapi.com.br/api/actions/sendMessage', [
    //             'number'  => '55999181805',
    //             'message' => $mensagem,
    //         ]);

    //     return back()->with('success', 'Lance enviado com sucesso!');
    // }

    public function store(Request $request)
    {
        $request->validate([
            'animal_event_id' => 'required|exists:animal_event,id',
            'event_id' => 'required|exists:events,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $userId = Auth::id();

        // 🔒 Verifica se já existe lance com o mesmo valor do mesmo usuário para o mesmo animal/evento
        $existingBid = Bid::where('user_id', $userId)
            ->where('animal_event_id', $request->animal_event_id)
            ->where('event_id', $request->event_id)
            ->where('amount', $request->amount)
            ->where('status', '!=', 2) // Ignora lances rejeitados
            ->first();

        if ($existingBid) {
            return back()->with('error', 'Você já enviou um lance com este mesmo valor para este animal.');
        }

        // ✅ Cria o novo lance
        Bid::create([
            'animal_event_id' => $request->animal_event_id,
            'event_id' => $request->event_id,
            'user_id' => $userId,
            'amount' => $request->amount,
            'status' => false,
        ]);

        // 🔍 Busca os dados relacionados
        $user   = Auth::user();
        $event  = Event::find($request->event_id);

        $animal = DB::table('animal_event')
            ->join('animals', 'animals.id', '=', 'animal_event.animal_id')
            ->where('animal_event.id', $request->animal_event_id)
            ->select('animals.name')
            ->first();

        // 💬 Monta mensagem
        $mensagem = "📣 Novo lance recebido!\n\n"
            . "Usuário: {$user->name}\n"
            . "E-mail: {$user->email}\n\n"
            . "Evento: {$event->name}\n"
            . "Animal: {$animal->name}\n"
            . "Valor do Lance: R$ " . number_format($request->amount, 2, ',', '.') . "\n\n"
            . "Verifique o painel administrativo para validar o lance.";

        // 📱 Envia notificação via WhatsApp (tratamento de erro)
        try {
            Http::withToken('esFDkhJ0D2G0M07nG5K9qCSbQDNC2xUQ5x8IxqHdJYYKHWUi6CxfxbIMfgiq')
                ->post('https://www.avisaapi.com.br/api/actions/sendMessage', [
                    'number'  => '55999181805',
                    'message' => $mensagem,
                ]);
        } catch (\Throwable $e) {
            Log::error('❌ Falha ao enviar mensagem WhatsApp: ' . $e->getMessage());
        }

        // 📧 Envia notificação por e-mail (tratamento de erro)
        try {
            Mail::send('emails.new-bid', [
                'user' => $user,
                'event' => $event,
                'animal' => $animal,
                'amount' => $request->amount,
            ], function ($mail) use ($event) {
                $mail->to(['lances@boqueiraoremates.com', 'guilhermelegramante@gmail.com'])
                    ->subject('Novo Lance Recebido - ' . $event->name)
                    ->from('contato@boqueiraoremates.com', 'Boqueirão Remates');
            });
        } catch (\Throwable $e) {
            Log::error('❌ Falha ao enviar e-mail de notificação: ' . $e->getMessage());
        }

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
