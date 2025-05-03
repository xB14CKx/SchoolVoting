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
    private $programAbbreviations = [
        'BSIT' => 'BS in Information Technology',
        'BSCS' => 'BS in Computer Science',
        'BSIS' => 'BS in Information Systems',
        'BLIS' => 'Bachelor of Library and Information Science',
        'BSEMC-Dig' => 'BS in Entertainment and Multimedia Computing – Digital Animation',
        'BSEMC-Gam' => 'BS in Entertainment and Multimedia Computing – Game Development',
        'BMA' => 'Bachelor of Multimedia Arts',
    ];

    public function index()
    {
        return view('votings.file-upload');
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,xlsx,xls|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();

            $studentsToInsert = [];
            $studentsForResponse = [];
            $skipped = 0;
            $added = 0;

            // Load all programs once and map by name
            $programs = Program::all()->keyBy('program_name');

            // Extract header and slice the rest
            $dataRows = array_slice($rows, 1);

            // Collect all emails to check for duplicates in bulk
            $emails = array_filter(array_map(fn($row) => $row[4] ?? '', $dataRows));
            $existingEmails = Student::whereIn('email', $emails)->pluck('email')->toArray();

            foreach ($dataRows as $row) {
                $id = $row[0] ?? null;
                $email = $row[4] ?? '';
                $programCode = $row[5] ?? '';
                $dob = $row[8] ?? null;

                // Validate minimal required data
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($id) || !is_numeric($id)) {
                    $skipped++;
                    continue;
                }

                // Format and validate date
                try {
                    $date = new \DateTime($dob);
                    $currentDate = new \DateTime();
                    $minDate = new \DateTime('1900-01-01');
                    if ($date > $currentDate || $date < $minDate) {
                        $skipped++;
                        continue;
                    }
                    $formattedDob = $date->format('Y-m-d');
                } catch (\Exception $e) {
                    $skipped++;
                    continue;
                }

                // Convert program abbreviation
                $programName = $this->programAbbreviations[$programCode] ?? $programCode;
                $program = $programs[$programName] ?? null;
                if (!$program) {
                    $skipped++;
                    continue;
                }

                // Check if email exists
                if (in_array($email, $existingEmails)) {
                    $skipped++;
                    continue;
                }

                // Prepare student data
                $studentData = [
                    'id' => $id,
                    'first_name' => $row[1] ?? '',
                    'middle_name' => !empty($row[2]) ? $row[2] : null,
                    'last_name' => $row[3] ?? '',
                    'email' => $email,
                    'program_id' => $program->program_id,
                    'year_level' => $row[6] ?? '',
                    'contact_number' => $row[7] ?? '',
                    'date_of_birth' => $formattedDob,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $studentsToInsert[] = $studentData;

                $studentsForResponse[] = [
                    'id' => $studentData['id'],
                    'name' => $studentData['first_name'] . ' ' . ($studentData['middle_name'] ? $studentData['middle_name'] . ' ' : '') . $studentData['last_name'],
                    'email' => $studentData['email'],
                    'program_name' => $program->program_name,
                    'year_level' => $studentData['year_level'],
                    'contact_number' => $studentData['contact_number'],
                    'date_of_birth' => $studentData['date_of_birth'],
                ];
            }

            // Batch insert valid students
            if (!empty($studentsToInsert)) {
                Student::insert($studentsToInsert);
                $added = count($studentsToInsert);
            }

            $path = $file->store('uploads');

            // Handle HTMX response
            if ($request->header('HX-Request') === 'true') {
                $html = '';
                if (empty($studentsForResponse)) {
                    $html = '<tr><td colspan="7" class="text-center">No new students added.</td></tr>';
                } else {
                    foreach ($studentsForResponse as $student) {
                        $html .= '<tr>';
                        $html .= '<td>' . htmlspecialchars($student['id']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($student['name']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($student['email']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($student['program_name']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($student['year_level']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($student['contact_number']) . '</td>';
                        $html .= '<td>' . htmlspecialchars($student['date_of_birth']) . '</td>';
                        $html .= '</tr>';
                    }
                }

                return response($html)->header('HX-Trigger', json_encode([
                    'uploadSuccess' => [
                        'added' => $added,
                        'skipped' => $skipped
                    ]
                ]));
            }

            return response()->json([
                'message' => "File uploaded successfully. Added $added new students, skipped $skipped duplicates or invalid records.",
                'students' => $studentsForResponse,
                'file_path' => $path,
            ]);
        } catch (\Exception $e) {
            $msg = 'Error processing file: ' . $e->getMessage();
            return response()->json(['message' => $msg], 500);
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
                        'program_name' => $student->program?->program_name ?? 'Unknown',
                        'year_level' => $student->year_level,
                        'contact_number' => $student->contact_number,
                        'date_of_birth' => $student->date_of_birth ?? '',
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
                        $html .= '<td>' . htmlspecialchars($student['date_of_birth']) . '</td>';
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
            return response()->json(['message' => 'Error fetching students: ' . $e->getMessage()], 500);
        }
    }
}
