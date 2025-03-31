<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration form with pre-filled student data.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            $studentId = session('eligible_student_id');

            Log::info('Checking eligibility for registration', [
                'student_id' => $studentId,
                'eligible_student_id' => $studentId,
            ]);

            if (!$studentId) {
                Log::info('Redirecting to eligibility due to session mismatch');
                return redirect()->route('eligibility')->with('error', 'Please check your eligibility before registering.');
            }

            $student = Student::find($studentId);

            if (!$student) {
                Log::info('Redirecting to eligibility due to student not found');
                return redirect()->route('eligibility')->with('error', 'Student ID not found. Please contact the administrator.');
            }

            return view('auth.register', compact('student'));
        } catch (\Exception $e) {
            Log::error('Failed to load registration form: ' . $e->getMessage());

            return redirect()->route('eligibility')->with('error', 'Failed to load registration form. Please try again.');
        }
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_initial' => 'nullable|string|max:10',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'program' => 'required|string|max:255',
            'year_level' => 'required|integer|min:1|max:5',
            'contact_number' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            // Merge first_name, middle_initial, and last_name into the name field
            $fullName = $validated['first_name'] . ' ' . ($validated['middle_initial'] ? $validated['middle_initial'] . ' ' : '') . $validated['last_name'];

            $user = User::create([
                'name' => $fullName,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'student', // Set a default role (adjust as needed)
            ]);

            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'student_id' => $validated['student_id'],
            ]);

            // Clear the eligible_student_id from the session
            session()->forget('eligible_student_id');

            // Fire the Registered event (Breeze's default behavior)
            event(new Registered($user));

            // Log the user in (Breeze's default behavior)
            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return redirect()->back()
                ->with('error', 'Failed to register. Please try again.')
                ->withInput();
        }
    }
}