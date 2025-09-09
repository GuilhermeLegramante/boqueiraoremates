<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bid;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'animal_event_id' => 'required|exists:animal_event,id',
            'event_id' => 'required|exists:events,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        Bid::create([
            'animal_event_id' => $request->animal_event_id,
            'event_id' => $request->event_id,
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'status' => false,
        ]);

        return back()->with('success', 'Lance enviado com sucesso!');
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
