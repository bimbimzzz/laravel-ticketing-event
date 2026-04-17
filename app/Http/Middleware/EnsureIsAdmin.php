<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (!str_ends_with(auth()->user()->email, '@admin.com')) {
            abort(403, 'Akses hanya untuk admin.');
        }

        return $next($request);
    }
}
