<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // 1. Cek login gak?
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 2. Cek Role user saat ini
        $userRole = Auth::user()->role;

        // 3. Logika Pengecekan
        // Kalau diminta 'admin', tapi user cuma 'kasir', TENDANG!
        if ($role == 'admin' && $userRole != 'admin') {
            // Redirect kasir yang nyasar ke halaman kasir mereka
            return redirect()->route('transactions.index')->with('error', 'Eits! Anda tidak punya akses ke halaman Bos.');
        }

        return $next($request);
    }
}