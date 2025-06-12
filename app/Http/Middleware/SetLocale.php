<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    // public function handle(Request $request, Closure $next)
    // {
    //     $locale = $request->input('locale', session('locale', config('app.locale')));

    //     if (in_array($locale, config('app.available_locales', ['en', 'vi']))) {
    //         App::setLocale($locale);
    //         session(['locale' => $locale]);
    //     }

    //     return $next($request);
    // }
}
