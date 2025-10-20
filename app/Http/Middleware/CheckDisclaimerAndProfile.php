<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CheckDisclaimerAndProfile
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
        // Skip check for guests, login, register, and password reset routes
        if (!Auth::check() || 
            $request->is('login') || 
            $request->is('register') || 
            $request->is('password/*') || 
            $request->is('reset-password*')) {
            return $next($request);
        }

        $user = Auth::user();

        // Check if user has completed their profile (has name and email)
        $profileComplete = !empty($user->name) && !empty($user->email);
        
        // For the new requirement, we want users to accept disclaimer every time they login
        // So we only check session, not the database record
        $disclaimerAccepted = Session::get('disclaimer_accepted', false);

        // If profile is not complete, redirect to profile completion page
        if (!$profileComplete) {
            // Allow access to profile page so users can complete their profile
            if ($request->is('profile*')) {
                return $next($request);
            }
            // Redirect to profile page for all other routes
            return redirect()->route('profile')->with('warning', 'Please complete your profile before proceeding.');
        }
        
        // If profile is complete but disclaimer not accepted in this session, show disclaimer
        if (!$disclaimerAccepted) {
            // Allow access to disclaimer page
            if ($request->is('disclaimer*')) {
                return $next($request);
            }
            // Redirect to disclaimer page for all other routes
            return redirect()->route('disclaimer');
        }

        // If both profile is complete and disclaimer accepted in this session, allow access
        return $next($request);
    }
}