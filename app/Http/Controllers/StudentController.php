<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


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

    /**
     * Upload and update the profile image for a student.
     */
    public function uploadProfileImage(Request $request)
    {
        \Log::info('User: ' . json_encode(auth()->user()));
        $student = \App\Models\Student::where('email', auth()->user()->email)->first();
        \Log::info('Student: ' . json_encode($student));

        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (!$student) {
            \Log::error('Student not found for user: ' . json_encode(auth()->user()));
            return back()->with('error', 'Student not found.');
        }

        // Ensure the profile_images directory exists
        if (!Storage::exists('public/profile_images')) {
            Storage::makeDirectory('public/profile_images');
        }

        // Store the uploaded image using the public disk
        $file = $request->file('profile_image');
        $filename = uniqid('profile_') . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('profile_images', $filename, 'public');

        // Delete old image if exists and not default, using the public disk
        if ($student->image && Storage::disk('public')->exists($student->image)) {
            Storage::disk('public')->delete($student->image);
        }

        // Save the relative path in the image column
        $student->image = 'profile_images/' . $filename;
        $student->save();
        \Log::info('Saved image: ' . $student->image);

        return back()->with('success', 'Profile image updated!');
    }
}