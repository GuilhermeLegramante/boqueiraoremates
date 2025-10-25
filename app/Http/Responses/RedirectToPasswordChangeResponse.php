<?php

namespace App\Http\Responses;

use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Http\RedirectResponse;

class RedirectToPasswordChangeResponse implements LoginResponse
{
    public function toResponse($request)
    {
        return redirect()->route('filament.admin.pages.first-password-change');
    }
}
