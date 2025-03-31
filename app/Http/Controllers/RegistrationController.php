<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;

class RegistrationController extends Controller
{
    /**
     * Display the registration form with pre-filled student data.
     *
     * @param  string  $student_id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($student_id)
    {
        try {
            $student = Student::find($student_id);
    
            if (!$student) {
                return redirect()->route('eligibility')->with('error', 'Student ID not found. Please contact the administrator.');
            }
    
            return view('registration', compact('student'));
        } catch (\Exception $e) {
            Log::error('Failed to load registration form: ' . $e->getMessage());
    
            return redirect()->route('eligibility')->with('error', 'Failed to load registration form. Please try again.');
        }
    }
    

    /**
     * Handle the registration form submission.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        // Validate the form input
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
            'password' => 'required|string|min:6|max:12|confirmed',
        ]);

        try {
            // Create a new user in the database
            $user = User::create([
                'name' => $validated['first_name'] . ' ' . ($validated['middle_initial'] ? $validated['middle_initial'] . ' ' : '') . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'student_id' => $validated['student_id'],
                'program' => $validated['program'],
                'year_level' => $validated['year_level'],
                'contact_number' => $validated['contact_number'],
                'date_of_birth' => $validated['date_of_birth'],
            ]);

            // Log successful registration
            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'student_id' => $validated['student_id'],
            ]);

            // Redirect to login page with success message
            return redirect()->route('login')->with('success', 'Registration successful! Please log in to continue.');
        } catch (\Exception $e) {
            Log::error('Registration failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return redirect()->back()
                ->with('error', 'Failed to register: ' . $e->getMessage())
                ->withInput();
        }
    }
}
