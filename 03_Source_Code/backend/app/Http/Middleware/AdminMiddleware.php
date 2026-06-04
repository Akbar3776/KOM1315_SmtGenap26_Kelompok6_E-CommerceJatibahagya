<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        if ($user->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini. Hanya admin yang diizinkan.');
        }

        return $next($request);
    }
}