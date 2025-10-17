<?php
// app/Http/Middleware/EnsureEmailIsVerified.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kullanıcı giriş yapmamışsa login'e yönlendir
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Email doğrulanmamışsa
        if (!$request->user()->email_verified_at) {
            // API istekleri için
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your email address is not verified.'
                ], 403);
            }

            // Web istekleri için
            return redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
