<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    public function index()
    {
        return view('votings.file-upload');
    }

    // Define a mapping of abbreviations to full program names
    private $programAbbreviations = [
        'BSIT' => 'BS in Information Technology',
        'BSCS' => 'BS in Computer Science',
        'BSIS' => 'BS in Information Systems',
        'BLIS' => 'Bachelor of Library and Information Science',
        'BSEMC-Dig' => 'BS in Entertainment and Multimedia Computing – Digital Animation',
        'BSEMC-Gam' => 'BS in Entertainment and Multimedia Computing – Game Development',
        'BMA' => 'Bachelor of Multimedia Arts',
    ];

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
                    'id' => $row[0] ?? null,
                    'first_name' => $row[1] ?? '',
                    'middle_name' => !empty($row[2]) ? $row[2] : null,
                    'last_name' => $row[3] ?? '',
                    'email' => $row[4] ?? '',
                    'program' => $row[5] ?? '',
                    'year_level' => $row[6] ?? '',
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
                        $date = new \DateTime($studentData['date_of_birth']);
                        $formattedDate = $date->format('Y-m-d');
                        $currentDate = new \DateTime();
                        $minDate = new \DateTime('1900-01-01');
                        if ($date > $currentDate || $date < $minDate) {
                            $skipped++;
                            continue;
                        }
                    } catch (\Exception $e) {
                        $skipped++;
                        continue;
                    }
                } else {
                    $skipped++;
                    continue;
                }

                // Check for program abbreviation and map to full name
                $programName = $studentData['program'];
                if (array_key_exists($studentData['program'], $this->programAbbreviations)) {
                    $programName = $this->programAbbreviations[$studentData['program']];
                }

                // Map program to program_id
                $program = Program::where('program_name', $programName)->first();
                if (!$program) {
                    $skipped++;
                    continue; // Skip if program not found
                }
                $studentData['program_id'] = $program->program_id;

                // Check if student already exists by email
                $existingStudent = Student::where('email', $studentData['email'])->first();

                if ($existingStudent) {
                    $skipped++;
                    $students[] = [
                        'id' => $existingStudent->id,
                        'name' => $existingStudent->first_name . ' ' . ($existingStudent->middle_name ? $existingStudent->middle_name . ' ' : '') . $existingStudent->last_name,
                        'email' => $existingStudent->email,
                        'program_id' => $existingStudent->program_id,
                        'year_level' => $existingStudent->year_level,
                        'contact_number' => $existingStudent->contact_number,
                        'date_of_birth' => $existingStudent->date_of_birth,
                    ];
                    continue;
                }

                // Create new student record
                $student = Student::create([
                    'id' => $studentData['id'],
                    'first_name' => $studentData['first_name'],
                    'middle_name' => $studentData['middle_name'],
                    'last_name' => $studentData['last_name'],
                    'email' => $studentData['email'],
                    'program_id' => $studentData['program_id'],
                    'year_level' => $studentData['year_level'],
                    'contact_number' => $studentData['contact_number'],
                    'date_of_birth' => $formattedDate,
                ]);

                $added++;

                // Add to response array
                $students[] = [
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . ($student->middle_name ? $student->middle_name . ' ' : '') . $student->last_name,
                    'email' => $student->email,
                    'program_name' => $program->program_name, // Use the resolved program name
                    'year_level' => $student->year_level,
                    'contact_number' => $student->contact_number,
                    'date_of_birth' => $student->date_of_birth ? $student->date_of_birth : '',
                ];
            }

            // Store the file
            $path = $file->store('uploads');

            // Check if the request is from HTMX
            if ($request->header('HX-Request') === 'true') {
                $html = '';
                foreach ($students as $student) {
                    $html .= '<tr>';
                    $html .= '<td>' . htmlspecialchars($student['id']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($student['name']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($student['email']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($student['program_name']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($student['year_level']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($student['contact_number']) . '</td>';
                    $html .= '<td>' . htmlspecialchars($student['date_of_birth'] ?? '') . '</td>';
                    $html .= '</tr>';
                }
                return response($html)->header('HX-Trigger', 'uploadSuccess');
            }

            return response()->json([
                'message' => "File uploaded successfully. Added $added new students, skipped $skipped duplicates or invalid records.",
                'students' => $students,
                'file_path' => $path,
            ]);
        } catch (\Exception $e) {
            if ($request->header('HX-Request') === 'true') {
                return response()->json([
                    'message' => 'Error processing file: ' . $e->getMessage()
                ], 500);
            }
            return response()->json([
                'message' => 'Error processing file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function fetchStudents(Request $request)
    {
        try {
            $year = $request->query('year', 2025);
            $students = Student::whereYear('created_at', $year)
                ->with('program')
                ->get()
                ->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->first_name . ' ' . ($student->middle_name ? $student->middle_name . ' ' : '') . $student->last_name,
                        'email' => $student->email,
                        'program_name' => $student->program ? $student->program->program_name : 'Unknown',
                        'year_level' => $student->year_level,
                        'contact_number' => $student->contact_number,
                        'date_of_birth' => $student->date_of_birth ? $student->date_of_birth : '',
                    ];
                })->toArray();

            if ($request->header('HX-Request') === 'true') {
                $html = '';
                if (empty($students)) {
                    $html = '<tr><td colspan="7" class="text-center">No students found for the selected year.</td></tr>';
                } else {
                    foreach ($students as $student) {
                        $html .= '<tr>';
                        $html .= '<td>' . htmlspecialchars($student['id']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($student['name']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($student['email']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($student['program_name']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($student['year_level']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($student['contact_number']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($student['date_of_birth'] ?? '') . '</td>';
                        $html .= '</tr>';
                    }
                }
                return response($html);
            }

            return response()->json([
                'message' => 'Students fetched successfully.',
                'students' => $students,
            ]);
        } catch (\Exception $e) {
            if ($request->header('HX-Request') === 'true') {
                return response()->json([
                    'message' => 'Error fetching students: ' . $e->getMessage()
                ], 500);
            }
            return response()->json([
                'message' => 'Error fetching students: ' . $e->getMessage()
            ], 500);
        }
    }
}
