<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();

            // For HTMX SPA: Return a redirect instruction
            if ($request->header('HX-Request')) {
                return response('<meta http-equiv="refresh" content="0;url=' . route('dashboard', absolute: false) . '">', 200)
                    ->header('HX-Redirect', route('dashboard', absolute: false));
            }
            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // For HTMX SPA: Return the login view with errors
            if ($request->header('HX-Request')) {
                return response()->view('auth.login', [
                    'email' => $request->input('email'),
                    'errors' => $e->errors(),
                ], 422);
            }
            throw $e; // Fallback for non-HTMX
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}