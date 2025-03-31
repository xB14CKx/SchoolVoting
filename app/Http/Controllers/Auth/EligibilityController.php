<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EligibilityController extends Controller
{
    // Define the HTMX header as a constant to avoid hardcoding and suppress linter warnings
    private const HX_PUSH_URL = 'HX-Push-Url';

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
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View|\Illuminate\Http\Response
     */
    public function check(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer',
        ]);

        // Check if this is an HTMX request
        $isHtmxRequest = $request->header('HX-Request') === 'true';

        try {
            // Query the student by ID
            $student = Student::where('id', $request->student_id)->first();

            if (!$student) {
                Log::info('Student not found during eligibility check', [
                    'student_id' => $request->student_id,
                ]);
                $errorMessage = 'Student ID not found. Please contact the administrator.';
                if ($isHtmxRequest) {
                    return response()->view('auth.eligibility', [
                        'error' => $errorMessage,
                    ])->header(self::HX_PUSH_URL, 'false');
                }
                return redirect()->route('register.eligibility')
                    ->with('error', $errorMessage);
            }

            // Check if the student is already registered by looking for a User with the same email
            $existingUser = User::where('email', $student->email)->first();
            if ($existingUser) {
                Log::info('Student is already registered', [
                    'student_id' => $student->id,
                    'email' => $student->email,
                    'user_id' => $existingUser->id,
                ]);
                $errorMessage = 'This student is already registered. Please log in or contact the administrator.';
                if ($isHtmxRequest) {
                    return response()->view('auth.eligibility', [
                        'error' => $errorMessage,
                    ])->header(self::HX_PUSH_URL, 'false');
                }
                return redirect()->route('register.eligibility')
                    ->with('error', $errorMessage);
            }

            // Check eligibility criteria
            // Example: Student must be in year 1-5 and have a valid email
            if ($student->year < 1 || $student->year > 5 || !$student->email) {
                Log::info('Student not eligible', [
                    'student_id' => $request->student_id,
                    'year' => $student->year,
                    'email' => $student->email,
                ]);
                $errorMessage = 'You are not eligible to register. Please contact the administrator.';
                if ($isHtmxRequest) {
                    return response()->view('auth.eligibility', [
                        'error' => $errorMessage,
                    ])->header(self::HX_PUSH_URL, 'false');
                }
                return redirect()->route('register.eligibility')
                    ->with('error', $errorMessage);
            }

            // Set the eligible student ID in the session
            session(['eligible_student_id' => $student->id]);

            Log::info('Student eligible, redirecting to registration', [
                'student_id' => $student->id,
            ]);

            if ($isHtmxRequest) {
                // For HTMX, return the registration form directly
                return response()->view('auth.register', compact('student'))
                    ->header(self::HX_PUSH_URL, route('register.form'));
            }

            return redirect()->route('register.form');
        } catch (\Exception $e) {
            Log::error('Eligibility check failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            $errorMessage = 'Failed to check eligibility. Please try again.';
            if ($isHtmxRequest) {
                return response()->view('auth.eligibility', [
                    'error' => $errorMessage,
                ])->header(self::HX_PUSH_URL, 'false');
            }

            return redirect()->route('register.eligibility')
                ->with('error', $errorMessage);
        }
    }
}