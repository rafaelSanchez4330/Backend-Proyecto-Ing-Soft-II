<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ConsultorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            \Illuminate\Support\Facades\Log::info('ConsultorMiddleware Check', [
                'user_id' => Auth::id(),
                'rol' => Auth::user()->rol,
                'is_consultor' => (Auth::user()->rol === 'consultor')
            ]);
        } else {
            \Illuminate\Support\Facades\Log::info('ConsultorMiddleware: User not authenticated');
        }

        if (!Auth::check() || Auth::user()->rol !== 'consultor') {
            return redirect()->route('login')->with('error', 'Acceso no autorizado');
        }
        return $next($request);
    }
}
