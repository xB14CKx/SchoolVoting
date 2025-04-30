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
            $student = Student::where('id', $request->student_id)->first();
    
            if (!$student) {
                Log::info('Student not found during eligibility check', [
                    'id' => $request->student_id,
                ]);
                $errorMessage = 'Student ID not found. Please contact the administrator.';
                if ($isHtmxRequest) {
                    return response()->view('auth.eligibility', [
                        'error' => $errorMessage,
                        'id' => $request->student_id,
                    ])->header(self::HX_PUSH_URL, 'false');
                }
                return redirect()->route('register.eligibility')->with('error', $errorMessage);
            }
            $existingUser = User::where('email', $student->email)->first();
            if ($existingUser) {
                Log::info('Student is already registered', [
                    'id' => $student->id,
                    'email' => $student->email,
                    'user_id' => $existingUser->id,
                ]);
                $errorMessage = 'This student is already registered. Please log in or contact the administrator.';
                if ($isHtmxRequest) {
                    return response()->view('auth.eligibility', [
                        'error' => $errorMessage,
                        'id' => $request->student_id,
                    ])->header(self::HX_PUSH_URL, 'false');
                }
                return redirect()->route('register.eligibility')->with('error', $errorMessage);
            }

            $validYearLevels = ['1st', '2nd', '3rd', '4th'];
                if (!in_array($student->year_level, $validYearLevels) || !$student->email) {
                Log::info('Student not eligible', [
                    'id' => $request->student_id,
                    'year_level' => $student->year_level,
                    'email' => $student->email,
                ]);
                $errorMessage = 'You are not eligible to register. Please contact the administrator.';
                if ($isHtmxRequest) {
                    return response()->view('auth.eligibility', [
                        'error' => $errorMessage,
                        'id' => $request->student_id,
                    ])->header(self::HX_PUSH_URL, 'false');
                }
                return redirect()->route('register.eligibility')->with('error', $errorMessage);
            }

            session(['eligible_student_id' => $student->id]);
            Log::info('Student eligible, proceeding to registration', [
                'id' => $student->id,
            ]);

            if ($isHtmxRequest) {
                return response()->view('auth.register', compact('student'))
                    ->header(self::HX_PUSH_URL, route('register.form'));
            }

            return redirect()->route('register.form');
        } catch (\Exception $e) {
            Log::error('Eligibility check failed: ' . $e->getMessage(), [
                'exception' => $e,
                'stack_trace' => $e->getTraceAsString(),
            ]);
            $errorMessage = 'Failed to check eligibility. Please try again.';
            if ($isHtmxRequest) {
                return response()->view('auth.eligibility', [
                    'error' => $errorMessage,
                    'id' => $request->id,
                ])->header(self::HX_PUSH_URL, 'false');
            }
            return redirect()->route('register.eligibility')->with('error', $errorMessage);
        }
    }
}
