<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Http\RedirectResponse;

class RedirectToPasswordChangeResponse implements LoginResponse
{
    public function toResponse($request): RedirectResponse
    {
        return redirect()->route('filament.pages.first-password-change');
    }
}
