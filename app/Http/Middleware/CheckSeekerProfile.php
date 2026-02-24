<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSeekerProfile
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->isSeeker() && !auth()->user()->seekerProfile) {
            return redirect()->route('seeker.profile.create')
                ->with('warning', 'Please complete your profile first.');
        }

        return $next($request);
    }
}