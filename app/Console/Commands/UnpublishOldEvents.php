<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UnpublishOldEvents extends Command
{
    protected $signature = 'events:unpublish-old';
    protected $description = 'Despublica eventos 5 horas após a data de término';

    public function handle()
    {
        $now = \Carbon\Carbon::now();

        $count = \App\Models\Event::where('published', true)
            ->where('finish_date', '<', $now->subHours(5))
            ->update(['published' => false]);

        Log::info("Comando events:unpublish-old executado. {$count} evento(s) despublicado(s).");

        $this->info("{$count} evento(s) despublicado(s).");
    }
}
