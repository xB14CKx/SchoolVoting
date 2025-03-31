<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EligibilityController extends Controller
{
    /**
     * Display the eligibility check form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('auth.eligibility');
    }

    /**
     * Check the student's eligibility and redirect to registration if eligible.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function check(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer',
        ]);

        try {
            // Query the student by ID
            $student = Student::where('id', $request->student_id)->first();

            if (!$student) {
                Log::info('Student not found during eligibility check', [
                    'student_id' => $request->student_id,
                ]);
                if ($request->header('HX-Request') === 'true') {
                    return view('auth.eligibility', [
                        'error' => 'Student ID not found. Please contact the administrator.',
                    ]);
                }
                return redirect()->route('register.eligibility')
                    ->with('error', 'Student ID not found. Please contact the administrator.');
            }

            // Check eligibility criteria
            // Example: Student must be in year 1-5 and have a valid email
            if ($student->year < 1 || $student->year > 5 || !$student->email) {
                Log::info('Student not eligible', [
                    'student_id' => $request->student_id,
                    'year' => $student->year,
                    'email' => $student->email,
                ]);
                if ($request->header('HX-Request') === 'true') {
                    return view('auth.eligibility', [
                        'error' => 'You are not eligible to register. Please contact the administrator.',
                    ]);
                }
                return redirect()->route('register.eligibility')
                    ->with('error', 'You are not eligible to register. Please contact the administrator.');
            }

            // Set the eligible student ID in the session
            session(['eligible_student_id' => $student->id]);

            Log::info('Student eligible, redirecting to registration', [
                'student_id' => $student->id,
            ]);

            // Check if this is an HTMX request
            if ($request->header('HX-Request') === 'true') {
                // For HTMX, return the registration form directly
                return view('auth.register', compact('student'));
            }

            return redirect()->route('register.form');
        } catch (\Exception $e) {
            Log::error('Eligibility check failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            if ($request->header('HX-Request') === 'true') {
                return view('auth.eligibility', [
                    'error' => 'Failed to check eligibility. Please try again.',
                ]);
            }

            return redirect()->route('register.eligibility')
                ->with('error', 'Failed to check eligibility. Please try again.');
        }
    }
}