<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EligibilityController extends Controller
{
    private const HX_PUSH_URL = 'HX-Push-Url';

    public function index()
    {
        return view('auth.eligibility');
    }

    public function check(Request $request)
    {
        $request->validate([
            'student_id' => 'required|integer',
        ]);

        $isHtmxRequest = $request->header('HX-Request') === 'true';

        Log::debug('HTMX Request Check', [
            'HX-Request' => $request->header('HX-Request'),
            'isHtmxRequest' => $isHtmxRequest,
            'request_headers' => $request->headers->all(),
        ]);

        try {
            // Check using the actual "id" column from the students table
            $student = Student::find((int) $request->student_id);

            if (!$student) {
                Log::info('Student not found during eligibility check', [
                    'student_id_input' => $request->student_id,
                ]);
                $errorMessage = 'Student ID not found. Please contact the administrator.';
                return $this->respondWithError($errorMessage, $request->student_id, $isHtmxRequest);
            }

            // Check if this student already has a user account
            $existingUser = User::where('email', $student->email)->first();
            if ($existingUser) {
                Log::info('Student already registered', [
                    'student_id' => $student->id,
                    'email' => $student->email,
                    'user_id' => $existingUser->id,
                ]);
                $errorMessage = 'This student is already registered. Please log in or contact the administrator.';
                return $this->respondWithError($errorMessage, $request->student_id, $isHtmxRequest);
            }

            // Eligibility check — adjust logic as needed

            if (!in_array($student->year_level, ['1st', '2nd', '3rd', '4th']) || !$student->email)
            {
                Log::info('Student not eligible', [
                    'student_id' => $student->id,
                    'year' => $student->year,
                    'email' => $student->email,
                ]);
                $errorMessage = 'You are not eligible to register. Please contact the administrator.';
                return $this->respondWithError($errorMessage, $request->student_id, $isHtmxRequest);
            }

            // Passed all checks
            session(['eligible_student_id' => $student->id]);
            Log::info('Student eligible for registration', [
                'student_id' => $student->id,
            ]);

            if ($isHtmxRequest) {
                return response()->view('auth.register', compact('student'))
                    ->header(self::HX_PUSH_URL, route('register.form'));
            }

            return redirect()->route('register.form');

        } catch (\Exception $e) {
            Log::error('Eligibility check failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $errorMessage = 'Failed to check eligibility. Please try again.';
            return $this->respondWithError($errorMessage, $request->student_id, $isHtmxRequest);
        }
    }

    /**
     * Handles both normal and HTMX error responses.
     */
    private function respondWithError(string $errorMessage, $student_id, bool $isHtmxRequest)
    {
        if ($isHtmxRequest) {
            return response()->view('auth.eligibility', [
                'error' => $errorMessage,
                'student_id' => $student_id,
            ])->header(self::HX_PUSH_URL, 'false');
        }

        return redirect()->route('register.eligibility')
            ->with('error', $errorMessage)
            ->withInput(['student_id' => $student_id]);
    }
}
