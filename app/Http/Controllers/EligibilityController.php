<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use Illuminate\Support\Facades\Cache;

class EligibilityController extends Controller
{
    public function index()
    {
        return view('eligibility');
    }

    public function check(Request $request)
    {
        // Validate the input student ID
        $validated = $request->validate([
            'student_id' => 'required|string|max:255',
        ]);

        try {
            // Cache the eligible student IDs for 24 hours to improve performance
            $eligibleStudentIds = Cache::remember('eligible_student_ids', 60 * 60 * 24, function () {
                // Import the Excel file from the excel directory
                $students = Excel::toCollection(new StudentsImport, 'excel/students.xlsx');
                // Flatten the collection (since toCollection returns a collection of collections)
                return $students->flatten();
            });

            // Check if the input student ID exists in the eligible list
            $isEligible = $eligibleStudentIds->contains($validated['student_id']);

            if ($isEligible) {
                return redirect()->route('eligibility')
                    ->with('success', 'You are eligible to vote!');
            } else {
                return redirect()->route('eligibility')
                    ->with('error', 'You are not eligible to vote. Please contact the administrator.');
            }
        } catch (\Exception $e) {
            return redirect()->route('eligibility')
                ->with('error', 'Failed to check eligibility: ' . $e->getMessage());
        }
    }
}