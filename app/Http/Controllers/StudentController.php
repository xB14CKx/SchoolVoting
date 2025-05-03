<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            // Import the Excel file
            Excel::import(new StudentsImport, $request->file('file'));

            return redirect()->back()->with('success', 'Students imported successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing students: ' . $e->getMessage());
        }
    }

    /**
     * Search for a student by ID and return JSON data.
     */
    public function search($id)
    {
        $student = \App\Models\Student::with('program')->find($id);
        if ($student) {
            return response()->json([
                'success' => true,
                'student' => [
                    'id' => $student->id,
                    'first_name' => $student->first_name,
                    'middle_name' => $student->middle_name,
                    'last_name' => $student->last_name,
                    'email' => $student->email,
                    'program_id' => $student->program_id,
                    'program_name' => $student->program ? $student->program->program_name : null,
                    'year_level' => $student->year_level,
                    'contact_number' => $student->contact_number,
                    'date_of_birth' => $student->date_of_birth,
                ]
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Student not found.']);
        }
    }
}