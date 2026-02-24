<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCompanyProfile
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->isCompany() && !auth()->user()->company) {
            return redirect()->route('company.profile.create')
                ->with('warning', 'Please complete your company profile first.');
        }

        return $next($request);
    }
}