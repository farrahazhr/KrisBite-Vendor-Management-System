<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        // Jika user tak login, biar middleware 'auth' halang dia (safety)
        if (!$user) {
            return redirect()->route('login');
        }

        // SEMAKAN STATUS: Kalau worker tapi belum di-approve
        if (in_array('worker', $roles) && $user->role == 'worker' && $user->status !== 'approved') {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akaun Worker anda masih menunggu kelulusan Admin.');
        }

        // Semakan role biasa
        if (!in_array($user->role, $roles)) {
            abort(403, 'RALAT 403: Akses Dilarang! Anda tiada kebenaran untuk capai halaman ini.');
        }

        return $next($request);
    }
}