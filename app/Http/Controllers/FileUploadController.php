<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
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
            $importer = new StudentsImport();
            $result = Excel::toCollection($importer, $file)->first();

            if (empty($result)) {
                Log::error('No data found in the uploaded file');
                return response()->json(['message' => 'No data found in the uploaded file'], 422);
            }

            $result = $importer->collection($result);

            $studentsForResponse = $result['studentsForResponse'];
            $added = $result['added'];
            $skipped = $result['skipped'];

            Log::info('Upload processing complete', [
                'added' => $added,
                'skipped' => $skipped,
                'total_processed' => $added + $skipped,
            ]);

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
            Log::error('Error processing file', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
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
