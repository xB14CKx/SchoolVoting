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

            // Use caching to reduce database queries
            $isEligible = Cache::remember("eligible_student_{$studentId}", 86400, function () use ($studentId) {
                return Student::where('id', $studentId)->exists();
            });

            Log::info('Eligibility check performed', [
                'student_id' => $studentId,
                'is_eligible' => $isEligible,
            ]);

            if ($isEligible) {
                Log::info('Redirecting to registration', ['student_id' => $studentId]);
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

    /**
     * Check eligibility for multiple student IDs in batch.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkBatch(Request $request)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'integer|exists:students,id',
        ]);

        try {
            $studentIds = $validated['student_ids'];
            $cacheKey = 'eligible_students_batch_' . md5(json_encode($studentIds));

            $results = Cache::remember($cacheKey, 86400, function () use ($studentIds) {
                $existingStudents = Student::whereIn('id', $studentIds)->pluck('id')->toArray();
                return array_fill_keys($existingStudents, true) + array_fill_keys($studentIds, false);
            });

            Log::info('Batch eligibility check performed', [
                'student_ids' => $studentIds,
                'results' => $results,
            ]);

            return response()->json(['success' => true, 'results' => $results]);
        } catch (\Exception $e) {
            Log::error('Batch eligibility check failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'error' => 'An error occurred while checking eligibility.'], 500);
        }
    }
}
