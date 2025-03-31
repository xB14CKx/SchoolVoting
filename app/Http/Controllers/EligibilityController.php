<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use Illuminate\Support\Facades\Cache;
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
        return view('eligibility');
    }

    /**
     * Check if a student is eligible to register and vote based on their ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function check(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|integer|exists:students,id',
        ]);
    
        try {
            $studentId = $validated['student_id'];
    
            $isEligible = Cache::remember("eligible_student_{$studentId}", 86400, function () use ($studentId) {
                return Student::where('id', $studentId)->exists();
            });
    
            Log::info('Eligibility check performed', [
                'student_id' => $studentId,
                'is_eligible' => $isEligible,
            ]);
    
            if ($isEligible) {
                $student = Student::find($studentId);
                Log::info('Rendering registration form', ['student_id' => $studentId]);
    
                // Store the student_id in the session
                session(['eligible_student_id' => $studentId]);
    
                if ($request->header('HX-Request') === 'true') {
                    Log::info('HTMX request detected, rendering registration view', ['student_id' => $studentId]);
                    $response = response()->view('registration', [
                        'student' => $student,
                        'success' => 'You are eligible to register!',
                    ]);
                    $response->header('HX-Push-Url', route('registration', ['student_id' => $studentId]));
                    return $response;
                }
    
                return redirect()->route('registration', ['student_id' => $studentId])
                    ->with('success', 'You are eligible to register!');
            }
    
            return redirect()->route('eligibility')
                ->with('error', 'You are not eligible to register and vote. Please contact the administrator.');
        } catch (\Exception $e) {
            Log::error('Eligibility check failed', ['error' => $e->getMessage()]);
    
            return redirect()->route('eligibility')
                ->with('error', 'An error occurred while checking eligibility. Please try again.');
        }
    }
}
