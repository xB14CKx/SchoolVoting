<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // Always check current password first
        if (!Hash::check($request->current_password, $user->password)) {
            // SweetAlert2 popup via session
            return back()->with('sweetalert_error', 'The current password you entered is incorrect.');
        }

        // Only validate the new password if the current password is correct
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('sweetalert_success', 'Password updated successfully!');
    }
}
