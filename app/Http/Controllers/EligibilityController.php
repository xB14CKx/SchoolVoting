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

            // Cache eligible student IDs for 24 hours
            $eligibleStudentIds = Cache::remember('eligible_student_ids', 60 * 60 * 24, function () use ($absolutePath) {
                // Import the Excel file using the absolute path
                $students = Excel::toCollection(new StudentsImport, $absolutePath);

                // Log the raw data for debugging
                Log::info('Raw Excel data', ['students' => $students->toArray()]);

                // Flatten and ensure IDs are strings (first column)
                $studentIds = $students->flatten()->map(function ($id) {
                    return (string) $id;
                })->filter()->values()->all();

                // Log the processed student IDs
                Log::info('Processed student IDs', ['student_ids' => $studentIds]);

                return $studentIds;
            });

            // Check eligibility
            $isEligible = in_array($validated['student_id'], $eligibleStudentIds);

            // Log the eligibility check result
            Log::info('Eligibility check', [
                'student_id' => $validated['student_id'],
                'eligible_student_ids' => $eligibleStudentIds,
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
}