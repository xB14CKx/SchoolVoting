<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    /**
     * Display the registration form with pre-filled student data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        try {
            // Get the student_id from the query parameter
            $studentId = $request->query('student_id');
            if (!$studentId) {
                return redirect()->route('eligibility')
                    ->with('error', 'Student ID is required to access the registration form.');
            }

            // Define the file path relative to storage/app
            $relativePath = 'excel/students.xlsx';
            $absolutePath = storage_path('app/' . $relativePath);

            // Verify the file exists
            if (!file_exists($absolutePath)) {
                throw new \Exception('The eligibility file (students.xlsx) is missing in storage/app/excel/. Please contact the administrator.');
            }

            // Load the Excel file into a collection
            $students = Excel::toCollection(new StudentsImport, $absolutePath);

            // Log the raw data for debugging
            Log::info('Raw Excel data for registration', ['students' => $students->toArray()]);

            // Create an instance of StudentsImport to use the getStudentById method
            $import = new StudentsImport();

            // Fetch the student details by ID
            $studentData = $import->getStudentById($students->first(), $studentId);

            if (!$studentData) {
                return redirect()->route('eligibility')
                    ->with('error', 'Student ID not found in the eligibility list. Please contact the administrator.');
            }

            // Log the fetched student data
            Log::info('Fetched student data for registration', ['student' => $studentData]);

            // Pass the student data to the view
            return view('registration', ['student' => $studentData]);
        } catch (\Exception $e) {
            Log::error('Failed to load registration form: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return redirect()->route('eligibility')
                ->with('error', 'Failed to load registration form: ' . $e->getMessage());
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

            // Log the successful registration
            Log::info('User registered successfully', [
                'user_id' => $user->id,
                'student_id' => $validated['student_id'],
            ]);

            // Redirect to a success page or login page
            return redirect()->route('login')
                ->with('success', 'Registration successful! Please log in to continue.');
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