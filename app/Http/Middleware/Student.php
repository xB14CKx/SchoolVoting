<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Student
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && strtolower(Auth::user()->role) === 'student') {
            return $next($request);
        }

        abort(403, 'Unauthorized: You do not have student access.');
    }
}
