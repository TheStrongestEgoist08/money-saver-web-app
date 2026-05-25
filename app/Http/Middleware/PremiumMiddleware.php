<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PremiumMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->user()->is_premium) {
            abort(403, 'Premium Access Required');
        }

        return $next($request);
    }
}
