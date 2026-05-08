<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    private $recipients;
    /**
     * Envia as notificações de um novo lance (WhatsApp e E-mail).
     */
    public function sendNewBidNotifications($user, $event, $animal, $amount)
    {
        // $this->sendWhatsApp($user, $event, $animal, $amount);
        // $this->recipients = ['lances@boqueiraoremates.com', 'guilhermelegramante@gmail.com'];
        $this->recipients = ['lances@boqueiraoremates.com'];

        $this->sendEmail($user, $event, $animal, $amount);
    }

    private function sendWhatsApp($user, $event, $animal, $amount)
    {
        $mensagem = "📣 Novo lance recebido!\n\n"
            . "Usuário: {$user->name}\n"
            . "E-mail: {$user->email}\n\n"
            . "Evento: {$event->name}\n"
            . "Lote: {$animal->lot_number}\n"
            . "Animal: {$animal->name}\n"
            . "Valor do Lance: R$ " . number_format($amount, 2, ',', '.') . "\n\n"
            . "Verifique o painel administrativo para validar o lance.";

        try {
            Http::withToken('SEU_TOKEN_AQUI')
                ->post('https://www.avisaapi.com.br/api/actions/sendMessage', [
                    'number'  => '55999181805',
                    'message' => $mensagem,
                ]);
        } catch (\Throwable $e) {
            Log::error('❌ Falha ao enviar WhatsApp: ' . $e->getMessage());
        }
    }

    private function sendEmail($user, $event, $animal, $amount)
    {
        $recipients = $this->recipients;

        try {
            Mail::send('emails.new-bid', [
                'user' => $user,
                'event' => $event,
                'animal' => $animal,
                'amount' => $amount,
            ], function ($mail) use ($event, $recipients) {
                $mail->to($recipients)
                    ->subject('Novo Lance Recebido - ' . $event->name)
                    ->from('contato@boqueiraoremates.com', 'Boqueirão Remates');
            });
        } catch (\Throwable $e) {
            Log::error('❌ Falha ao enviar e-mail: ' . $e->getMessage());
        }
    }
}
