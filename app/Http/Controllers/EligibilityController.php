<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
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
            // Define the file path relative to storage/app
            $relativePath = 'excel/students.xlsx';
            $absolutePath = storage_path('app/' . $relativePath);

            // Log the paths and existence checks for debugging
            Log::info('Checking file existence', [
                'relative_path' => $relativePath,
                'absolute_path' => $absolutePath,
                'exists_with_file' => file_exists($absolutePath),
                'exists_with_storage' => Storage::disk('local')->exists($relativePath),
            ]);

            // Verify the file exists using the absolute path
            if (!file_exists($absolutePath)) {
                throw new \Exception('The eligibility file (students.xlsx) is missing in storage/app/excel/. Please contact the administrator.');
            }

            // Load the Excel file into a collection
            $students = Excel::toCollection(new StudentsImport, $absolutePath);

            // Log the raw data for debugging
            Log::info('Raw Excel data', ['students' => $students->toArray()]);

            // Create an instance of StudentsImport to use the checkStudentId method
            $import = new StudentsImport();

            // Check if the student ID exists in the Excel file (skipping the first row)
            $isEligible = Cache::remember(
                "eligible_student_id_{$validated['student_id']}",
                60 * 60 * 24, // Cache for 24 hours
                function () use ($import, $students, $validated) {
                    return $import->checkStudentId($students->first(), $validated['student_id']);
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
            // Define the file path relative to storage/app
            $relativePath = 'excel/students.xlsx';
            $absolutePath = storage_path('app/' . $relativePath);

            // Log the paths and existence checks for debugging
            Log::info('Checking file existence for batch', [
                'relative_path' => $relativePath,
                'absolute_path' => $absolutePath,
                'exists_with_file' => file_exists($absolutePath),
                'exists_with_storage' => Storage::disk('local')->exists($relativePath),
            ]);

            // Verify the file exists using the absolute path
            if (!file_exists($absolutePath)) {
                throw new \Exception('The eligibility file (students.xlsx) is missing in storage/app/excel/. Please contact the administrator.');
            }

            // Load the Excel file into a collection
            $students = Excel::toCollection(new StudentsImport, $absolutePath);

            // Log the raw data for debugging
            Log::info('Raw Excel data for batch', ['students' => $students->toArray()]);

            // Create an instance of StudentsImport to use the checkStudentIdsBatch method
            $import = new StudentsImport();

            // Check the student IDs in batch (using cache for the entire batch result)
            $studentIds = $validated['student_ids'];
            $cacheKey = 'eligible_student_ids_batch_' . md5(implode('_', $studentIds));
            $results = Cache::remember(
                $cacheKey,
                60 * 60 * 24, // Cache for 24 hours
                function () use ($import, $students, $studentIds) {
                    return $import->checkStudentIdsBatch($students->first(), $studentIds);
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