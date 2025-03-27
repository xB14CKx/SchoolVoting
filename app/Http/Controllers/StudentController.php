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
}