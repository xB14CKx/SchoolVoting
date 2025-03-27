<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student; // Use the Student model to query the database
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
        // Validate the input student ID
        $validated = $request->validate([
            'student_id' => 'required|string|max:255',
        ]);

        try {
            // Check if the student exists in the database
            $isEligible = Cache::remember(
                "eligible_student_id_{$validated['student_id']}",
                60 * 60 * 24, // Cache for 24 hours
                function () use ($validated) {
                    return Student::where('id', $validated['student_id'])->exists();
                }
            );

            // Log the eligibility check result
            Log::info('Eligibility check', [
                'student_id' => $validated['student_id'],
                'is_eligible' => $isEligible,
            ]);

            if ($isEligible) {
                // Redirect to registration with student_id as a query parameter
                return redirect()->route('registration', ['student_id' => $validated['student_id']])
                    ->with('success', 'You are eligible to register! Please complete the registration form to vote.');
            }

            return redirect()->route('eligibility')
                ->with('error', 'You are not eligible to register and vote. Please contact the administrator.');
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Eligibility check failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            // Return a user-friendly error message
            return redirect()->route('eligibility')
                ->with('error', 'Failed to check eligibility: ' . $e->getMessage());
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
        // Validate the input student IDs (expecting an array)
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'required|string|max:255',
        ]);

        try {
            // Check the student IDs in batch using the database
            $studentIds = $validated['student_ids'];
            $cacheKey = 'eligible_student_ids_batch_' . md5(implode('_', $studentIds));
            $results = Cache::remember(
                $cacheKey,
                60 * 60 * 24, // Cache for 24 hours
                function () use ($studentIds) {
                    // Query the database for all matching student IDs
                    $existingStudents = Student::whereIn('id', $studentIds)
                        ->pluck('id')
                        ->map(fn($id) => (string) $id)
                        ->toArray();

                    // Build the results array
                    $results = [];
                    foreach ($studentIds as $studentId) {
                        $studentId = (string) $studentId;
                        $results[$studentId] = in_array($studentId, $existingStudents);
                    }

                    return $results;
                }
            );

            // Log the batch eligibility check result
            Log::info('Batch eligibility check', [
                'student_ids' => $studentIds,
                'results' => $results,
            ]);

            // Return the results as a JSON response
            return response()->json([
                'success' => true,
                'results' => $results,
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Batch eligibility check failed: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            // Return an error response
            return response()->json([
                'success' => false,
                'error' => 'Failed to check eligibility: ' . $e->getMessage(),
            ], 500);
        }
    }
}