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
            // Custom: Check if email exists
            $user = \App\Models\User::where('email', $request->input('email'))->first();
            if (!$user) {
                $errorMessage = 'The email is not registered';
                if ($request->header('HX-Request')) {
                    return response()->json([
                        'error' => $errorMessage,
                    ], 422)->header('HX-Trigger', 'showError');
                }
                return back()->withErrors(['email' => $errorMessage]);
            }

            // Try to authenticate (password check)
            if (!\Illuminate\Support\Facades\Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
                $errorMessage = 'Incorrect password';
                if ($request->header('HX-Request')) {
                    return response()->json([
                        'error' => $errorMessage,
                    ], 422)->header('HX-Trigger', 'showError');
                }
                return back()->withErrors(['email' => $errorMessage]);
            }

            $request->session()->regenerate();

            // For HTMX SPA: Return a success message and redirect
            if ($request->header('HX-Request')) {
                return response()->json([
                    'success' => 'Successfully logged in!',
                    'redirect' => route('dashboard', absolute: false),
                ], 200)->header('HX-Redirect', route('dashboard', absolute: false));
            }
            return redirect()->intended(route('dashboard', absolute: false))
                ->with('success', 'Successfully logged in!');
        } catch (\Exception $e) {
            $errorMessage = 'An unexpected error occurred.';
            if ($request->header('HX-Request')) {
                return response()->json([
                    'error' => $errorMessage,
                ], 422)->header('HX-Trigger', 'showError');
            }
            return back()->withErrors(['email' => $errorMessage]);
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
