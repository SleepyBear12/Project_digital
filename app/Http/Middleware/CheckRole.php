<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if ($role === 'admin' && !$user->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya admin yang boleh mengakses.');
        }

        if ($role === 'kasir' && !($user->isAdmin() || $user->isKasir())) {
            abort(403, 'Akses ditolak.');
        }

        return $next($request);
    }
}

