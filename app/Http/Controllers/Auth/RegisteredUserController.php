<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Program;
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
                Log::info('Redirecting to eligibility due to session mismatch');
                return redirect()->route('register.eligibility')
                    ->with('error', 'Please check your eligibility before registering.');
            }

            $student = Student::with('program')->find($studentId);

            // Fetch the selected program details
            $program = Program::find($student->program_id);
            $programName = $program ? $program->name : 'Unknown Program';
            $programId = $student->program_id ?? null;

            return view('auth.register', compact('student', 'programs', 'programName', 'programId'));
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
                'program_id' => 'required|exists:programs,program_id',
                'year_level' => 'required|in:1st,2nd,3rd,4th',
                'contact_number' => 'required|string|max:20',
                'date_of_birth' => 'required|date|before:today',
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed during registration', [
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);
            // Store validation errors in session for SweetAlert2
            $errorMessage = implode(', ', array_map(function ($errors) {
                return implode(', ', $errors);
            }, $e->errors()));
            return redirect()->back()->with('sweetalert_error', $errorMessage)->withInput();
        }

        try {
            // Check if a user with the same email and password already exists
            $existingUser = User::where('email', $validated['email'])->first();

            if ($existingUser && Hash::check($validated['password'], $existingUser->password)) {
                // If user exists with the same email and password, log them in
                Auth::login($existingUser);

                Log::info('User logged in using existing credentials', [
                    'user_id' => $existingUser->id,
                    'is_authenticated' => Auth::check(),
                ]);

                // Redirect based on user role
                if ($existingUser->role === 'student') {
                    return redirect()->route('student.dashboard')
                        ->with('success', 'Login successful! Welcome back.');
                } elseif ($existingUser->role === 'admin') {
                    return redirect()->route('admin.dashboard')
                        ->with('success', 'Login successful! Welcome back.');
                }

                return redirect()->route('home')
                    ->with('success', 'Login successful! Welcome back.');
            }

            // Combine the name fields
            $fullName = trim($validated['first_name'] . ' ' . ($validated['middle_name'] ? $validated['middle_name'] . ' ' : '') . $validated['last_name']);

            // Prepare user data
            $userData = [
                'name' => $fullName,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => Role::Student->value,
            ];

            Log::info('Attempting to create user', $userData);

            // Create the user
            $user = User::create($userData);

            // Log the successful registration
            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'id' => $validated['student_id'], // Fixed to use student_id
            ]);

            // Fetch the existing student record
            $student = Student::find($validated['student_id']);

            if (!$student) {
                Log::error('Student not found during registration', [
                    'student_id' => $validated['student_id'],
                ]);
                throw new \Exception('Student not found after eligibility check.');
            }

            // Update the student record with validated data
            $student->update([
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'program_id' => $validated['program_id'],
                'year_level' => $validated['year_level'],
                'contact_number' => $validated['contact_number'],
                'date_of_birth' => $validated['date_of_birth'],
                'updated_at' => now(),
            ]);

            // Clear the eligible_student_id from the session
            session()->forget('eligible_student_id');

            // Fire the Registered event
            event(new Registered($user));

            // Log the user in
            Auth::login($user);

            // Log the login status for debugging
            Log::info('User logged in after registration', [
                'user_id' => $user->id,
                'is_authenticated' => Auth::check(),
            ]);

            // Redirect based on user role
            if ($user->role === 'student') {
                return redirect()->route('student.dashboard')
                    ->with('success', 'Registration successful! Welcome to the platform.');
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Registration successful! Welcome to the platform.');
            }

            // Fallback redirect if role is not recognized
            return redirect()->route('home')
                ->with('success', 'Registration successful! Welcome to the platform.');
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);

            // Store the error in session for SweetAlert2
            return redirect()->back()
                ->with('sweetalert_error', 'Failed to register: ' . $e->getMessage())
                ->withInput();
        }
    }
}
