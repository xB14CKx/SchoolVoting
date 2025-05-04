<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            $studentsToInsert = [];
            $studentsForResponse = [];
            $skipped = 0;
            $added = 0;

            $programs = Program::all()->keyBy('program_name');
            $dataRows = array_slice($rows, 1);
            $emails = array_filter(array_map(fn($row) => $row[4] ?? '', $dataRows));
            $existingEmails = Student::whereIn('email', $emails)->pluck('email')->toArray();

            foreach ($dataRows as $index => $row) {
                $id = $row[0] ?? null;
                $email = $row[4] ?? '';
                $programCode = $row[5] ?? '';
                $dob = $row[8] ?? null;
                $sex = $row[9] ?? null;

                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($id) || !is_numeric($id)) {
                    Log::warning('Skipped row due to invalid data', ['row' => $index + 2, 'id' => $id, 'email' => $email]);
                    $skipped++;
                    continue;
                }

                $formattedDob = null;
                try {
                    Log::debug('Raw DOB value:', ['row' => $index + 2, 'dob' => $dob]);

                    if (is_numeric($dob)) {
                        $date = Date::excelToDateTimeObject($dob);
                    } elseif (preg_match('/^\=DATE\((\d+),\s*(\d+),\s*(\d+)\)$/i', $dob, $matches)) {
                        $year = (int)$matches[1];
                        $month = (int)$matches[2];
                        $day = (int)$matches[3];
                        $date = new \DateTime();
                        $date->setDate($year, $month, $day);
                    } else {
                        $date = \DateTime::createFromFormat('m/d/Y', $dob)
                            ?: \DateTime::createFromFormat('d/m/Y', $dob)
                            ?: new \DateTime($dob);
                    }

                    $currentDate = new \DateTime();
                    $minDate = new \DateTime('1900-01-01');
                    if ($date > $currentDate || $date < $minDate) {
                        Log::warning('Skipped row due to invalid date range', ['row' => $index + 2, 'dob' => $dob]);
                        $skipped++;
                        continue;
                    }

                    $formattedDob = $date->format('Y-m-d');
                } catch (\Exception $e) {
                    Log::warning('Skipped row due to DOB parse failure', ['row' => $index + 2, 'dob' => $dob, 'error' => $e->getMessage()]);
                    $skipped++;
                    continue;
                }

                $programName = $this->programAbbreviations[$programCode] ?? $programCode;
                $program = $programs[$programName] ?? null;
                if (!$program) {
                    Log::warning('Skipped row due to unrecognized program', ['row' => $index + 2, 'program_code' => $programCode]);
                    $skipped++;
                    continue;
                }

                if (in_array($email, $existingEmails)) {
                    Log::warning('Skipped row due to duplicate email', ['row' => $index + 2, 'email' => $email]);
                    $skipped++;
                    continue;
                }

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
                    'sex' => $sex,
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
                    'sex' => $sex,
                ];
            }

            if (!empty($studentsToInsert)) {
                Student::insert($studentsToInsert);
                $added = count($studentsToInsert);
            }

            $path = $file->store('uploads');

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
            return response()->json(['message' => 'Error processing file: ' . $e->getMessage()], 500);
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
