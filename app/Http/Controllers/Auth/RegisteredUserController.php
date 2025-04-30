<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Program; // Import Program model for reference
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use App\Enums\Role;

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
        $programs = Program::all();
        $studentId = session('eligible_student_id');

        Log::info('Checking eligibility for registration', [
            'id' => $studentId,
            'eligible_student_id' => $studentId,
        ]);

        if (!$studentId) {
            return redirect()->route('register.eligibility')
                ->with('error', 'Please check your eligibility before registering.');
        }

        // Fetch student with program relationship
        $student = Student::with('program')->find($studentId);

        if (!$student) {
            return redirect()->route('register.eligibility')
                ->with('error', 'Student ID not found. Please contact the administrator.');
        }

        // Safely get the program name
        $programName = optional($student->program)->name ?? 'Unknown Program';

        return view('auth.register', compact('student', 'programs', 'programName'));
    } catch (\Exception $e) {
        Log::error('Failed to load registration form: ' . $e->getMessage());

        return redirect()->route('register.eligibility')
            ->with('error', 'Failed to load registration form. Please try again.');
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
        Log::info('Received registration request', [
            'request_data' => $request->all(),
        ]);

        try {
            // Validate the request
            $validated = $request->validate([
                'student_id' => 'required|string|max:255',
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:10',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'program_id' => 'required|exists:programs,id', // Ensure program_id exists in programs table
                'year_level' => 'required|in:1st,2nd,3rd,4th', // ENUM values for year_level
                'contact_number' => 'required|string|max:20',
                'date_of_birth' => 'required|date|before:today',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed during registration', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);
            throw $e; // Re-throw to let Laravel handle the redirect with errors
        }

        try {
            // Combine the name fields
            $fullName = trim($validated['first_name'] . ' ' . ($validated['middle_name'] ? $validated['middle_name'] . ' ' : '') . $validated['last_name']);

            // Prepare user data
            $userData = [
                'name' => $fullName,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => Role::Student->value, // Use the Role enum value
            ];

            Log::info('Attempting to create user', $userData);

            // Create the user
            $user = User::create($userData);

            // Log the successful registration
            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'id' => $validated['student_id'],
            ]);

            // Fetch the program name by program_id
            $program = Program::find($validated['program_id']);
            $programName = $program ? $program->name : 'Unknown Program'; // Default to 'Unknown Program' if program not found

            // Create the student record and associate it with the correct program_id
            $studentData = [
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'program_id' => $validated['program_id'],
                'program_name' => $programName, // Add program name to student data
                'year_level' => $validated['year_level'],
                'contact_number' => $validated['contact_number'],
                'date_of_birth' => $validated['date_of_birth'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Insert student data
            $student = Student::create($studentData);

            // Clear the eligible_student_id from the session
            session()->forget('eligible_student_id');

            // Fire the Registered event (Breeze's default behavior)
            event(new Registered($user));

            // Log the user in
            Auth::login($user);

            // Log the login status for debugging
            Log::info('User logged in after registration', [
                'user_id' => $user->id,
                'is_authenticated' => Auth::check(),
            ]);

            // Redirect to the landing page (assuming the landing page route is 'home')
            return redirect()->route('home')->with('success', 'Registration successful! Welcome to the platform.');
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to register: ' . $e->getMessage())
                ->withInput();
        }
    }
}
