<?php

namespace App\Http\Controllers;

use App\Support\Locale;
use Illuminate\Http\RedirectResponse;

class LocaleController extends Controller
{
    /**
     * Switch the visitor's locale (stored in session).
     */
    public function switch(string $locale): RedirectResponse
    {
        abort_unless(Locale::isSupported($locale), 404);

        session(['locale' => $locale]);

        return back();
    }
}
