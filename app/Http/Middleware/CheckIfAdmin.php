<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class CheckIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if ($request->user()->roles->pluck('name')->contains('administrator')) {
                return $next($request);
            } else {
                Auth::logout();
                return redirect()->route('login')->withErrors([
                    'You dont have access!'
                ]);
            }
        } else {
            return redirect()->route('login')->withErrors([
                'Session has expired. Please log in again.'
            ]);
        }

        return $next($request);
    }
}
