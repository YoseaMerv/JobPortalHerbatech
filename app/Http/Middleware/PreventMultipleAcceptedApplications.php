<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class PreventMultipleAcceptedApplications
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Cek apakah user sudah login dan memiliki lamaran dengan status 'accepted'
        if (Auth::check() && $request->user()->applications()->where('status', 'accepted')->exists()) {
            return redirect()->route('seeker.dashboard')->with('error', 'Anda sudah diterima di satu lowongan dan tidak dapat melamar lagi.');
        }

        // 2. WAJIB: Teruskan request jika kondisi di atas tidak terpenuhi
        return $next($request);
    }
}
