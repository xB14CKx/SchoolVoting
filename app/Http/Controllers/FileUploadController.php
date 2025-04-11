<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    public function index()
    {
        return view('file-upload');
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,xlsx,xls|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Skip header row
            $students = [];
            $skipped = 0;
            $added = 0;

            foreach (array_slice($rows, 1) as $row) {
                // Map the row data to the database fields
                $studentData = [
                    'id' => $row[0] ?? null, // ID is provided in the spreadsheet (non-auto-incrementing)
                    'first_name' => $row[1] ?? '',
                    'middle_initial' => !empty($row[2]) ? $row[2] : null,
                    'last_name' => $row[3] ?? '',
                    'email' => $row[4] ?? '',
                    'program' => $row[5] ?? '',
                    'year' => $row[6] ?? 0,
                    'contact_number' => $row[7] ?? '',
                    'date_of_birth' => $row[8] ?? null,
                ];

                // Skip if email is empty or invalid
                if (empty($studentData['email']) || !filter_var($studentData['email'], FILTER_VALIDATE_EMAIL)) {
                    $skipped++;
                    continue;
                }

                // Skip if ID is empty or invalid (since ID is required and non-auto-incrementing)
                if (empty($studentData['id']) || !is_numeric($studentData['id'])) {
                    $skipped++;
                    continue;
                }

                // Validate and format date_of_birth to follow MySQL date rules (YYYY-MM-DD)
                $formattedDate = null;
                if (!empty($studentData['date_of_birth'])) {
                    try {
                        // Attempt to parse the date from the spreadsheet
                        $date = new \DateTime($studentData['date_of_birth']);
                        // Format the date to MySQL-compatible format (YYYY-MM-DD)
                        $formattedDate = $date->format('Y-m-d');

                        // Validate that the date is within a reasonable range (e.g., not in the future and not too old)
                        $currentDate = new \DateTime();
                        $minDate = new \DateTime('1900-01-01'); // Arbitrary minimum date
                        if ($date > $currentDate || $date < $minDate) {
                            $skipped++;
                            continue;
                        }
                    } catch (\Exception $e) {
                        // If date parsing fails, skip the record
                        $skipped++;
                        continue;
                    }
                } else {
                    // If date_of_birth is empty, set to null (since the field is not nullable in the schema)
                    $skipped++;
                    continue;
                }

                // Check if student already exists by email
                $existingStudent = Student::where('email', $studentData['email'])->first();

                if ($existingStudent) {
                    $skipped++;
                    // Add to response array even if skipped
                    $students[] = [
                        'id' => $existingStudent->id,
                        'name' => $existingStudent->first_name . ' ' . ($existingStudent->middle_initial ? $existingStudent->middle_initial . ' ' : '') . $existingStudent->last_name,
                        'email' => $existingStudent->email,
                        'program' => $existingStudent->program,
                        'year_level' => $existingStudent->year_level, // Using accessor
                        'contact_number' => $existingStudent->contact_number,
                        'date_of_birth' => $existingStudent->date_of_birth,
                    ];
                    continue;
                }

                // Create new student record
                $student = Student::create([
                    'id' => $studentData['id'], // Explicitly set the ID
                    'first_name' => $studentData['first_name'],
                    'middle_initial' => $studentData['middle_initial'],
                    'last_name' => $studentData['last_name'],
                    'email' => $studentData['email'],
                    'program' => $studentData['program'],
                    'year' => $studentData['year'],
                    'contact_number' => $studentData['contact_number'],
                    'date_of_birth' => $formattedDate, // Use the formatted date
                ]);

                $added++;

                // Add to response array
                $students[] = [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . ($student->middle_initial ? $student->middle_initial . ' ' : '') . $student->last_name,
                    'email' => $student->email,
                    'program' => $student->program,
                    'year_level' => $student->year_level, // Using accessor
                    'contact_number' => $student->contact_number,
                    'date_of_birth' => $student->date_of_birth ? $student->date_of_birth : '',
                ];
            }

            // Store the file
            $path = $file->store('uploads');

            // Check if the request is from HTMX
            if ($request->header('HX-Request') === 'true') {
                // Return HTML for HTMX to update the table
                $html = '';
                foreach ($students as $student) {
                    $html .= '<tr>';
                    $html .= '<td>' . htmlspecialchars($student['id']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($student['name']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($student['email']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($student['program']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($student['year_level']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($student['contact_number']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($student['date_of_birth'] ?? '') . '</td>';
                    $html .= '</tr>';
                }
                return response($html)->header('HX-Trigger', 'uploadSuccess');
            }

            // For non-HTMX requests, return JSON
            return response()->json([
                'message' => "File uploaded successfully. Added $added new students, skipped $skipped duplicates or invalid records.",
                'students' => $students,
                'file_path' => $path,
            ]);

        } catch (\Exception $e) {
            // For HTMX requests, return the error as JSON (will be handled by hx-on::after-request)
            if ($request->header('HX-Request') === 'true') {
                return response()->json([
                    'message' => 'Error processing file: ' . $e->getMessage()
                ], 500);
            }

            // For non-HTMX requests, return JSON as before
            return response()->json([
                'message' => 'Error processing file: ' . $e->getMessage()
            ], 500);
        }
    }
}
