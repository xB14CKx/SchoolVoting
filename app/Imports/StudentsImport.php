<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Program;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class StudentsImport implements ToCollection, WithHeadingRow
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

    private $yearLevelMapping = [
        '1' => '1st',
        '2' => '2nd',
        '3' => '3rd',
        '4' => '4th',
    ];

    public function collection(Collection $rows)
    {
        $programs = Program::all()->keyBy('program_name');
        $existingStudents = Student::all()->keyBy('student_id'); // Use student_id for duplicate checks
        $studentsForResponse = [];
        $skipped = 0;
        $added = 0;

        $rows->chunk(100)->each(function ($chunk) use ($programs, &$existingStudents, &$studentsForResponse, &$skipped, &$added) {
            foreach ($chunk as $index => $row) {
                $email = $row['email'] ?? '';
                $programCode = trim($row['program'] ?? '');
                $studentId = $row['id'] ?? null;

                // Validate required fields
                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Log::warning('Skipped row due to invalid or missing email', [
                        'row' => $index + 2,
                        'email' => $email
                    ]);
                    $skipped++;
                    continue;
                }

                if (empty($studentId) || !is_numeric($studentId)) {
                    Log::warning('Skipped row due to invalid or missing student_id', [
                        'row' => $index + 2,
                        'student_id' => $studentId
                    ]);
                    $skipped++;
                    continue;
                }

                $programName = $this->programAbbreviations[$programCode] ?? $programCode;
                $program = $programs[$programName] ?? null;
                if (!$program) {
                    Log::warning('Skipped row due to unrecognized program', [
                        'row' => $index + 2,
                        'program_code' => $programCode,
                        'mapped_program_name' => $programName,
                        'available_programs' => $programs->keys()->toArray()
                    ]);
                    $skipped++;
                    continue;
                }

                // Check for duplicate student_id
                $existingStudent = $existingStudents[$studentId] ?? null;
                if ($existingStudent && $existingStudent->email != $email) {
                    Log::warning('Skipped row due to duplicate student_id with different email', [
                        'row' => $index + 2,
                        'student_id' => $studentId,
                        'existing_email' => $existingStudent->email,
                        'new_email' => $email
                    ]);
                    $skipped++;
                    continue;
                }

                // Map and validate year_level
                $yearLevel = $row['year'] ?? '';
                $yearLevel = $this->yearLevelMapping[$yearLevel] ?? $yearLevel;
                if (!in_array($yearLevel, ['1st', '2nd', '3rd', '4th'])) {
                    Log::warning('Skipped row due to invalid year_level', [
                        'row' => $index + 2,
                        'year_level' => $yearLevel
                    ]);
                    $skipped++;
                    continue;
                }

                // Validate sex
                $sex = $row['sex'] ?? '';
                if (!in_array($sex, ['Male', 'Female'])) {
                    Log::warning('Skipped row because sex is not Male or Female', [
                        'row' => $index + 2,
                        'sex' => $sex
                    ]);
                    $skipped++;
                    continue;
                }

                $studentData = [
                    'student_id' => $studentId,
                    'first_name' => $row['first_name'] ?? '',
                    'middle_name' => $row['middle_name'] ?? null,
                    'last_name' => $row['last_name'] ?? '',
                    'email' => $email,
                    'program_id' => $program->program_id,
                    'year_level' => $yearLevel,
                    'contact_number' => (string) ($row['contact'] ?? ''),
                    'date_of_birth' => $this->parseDate($row['date_of_birth']),
                    'sex' => $sex,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                try {
                    $student = Student::updateOrCreate(
                        ['student_id' => $studentId],
                        $studentData
                    );

                    if ($student->wasRecentlyCreated) {
                        $added++;
                        Log::info('Added new student', ['student_id' => $studentId, 'email' => $email]);
                    } else {
                        Log::info('Updated existing student', ['student_id' => $studentId, 'email' => $email]);
                    }

                    $existingStudents[$studentId] = $student;

                    $studentsForResponse[] = [
                        'id' => (string) $studentId,
                        'name' => trim(($row['first_name'] ?? '') . ' ' . ($row['middle_name'] ?? '') . ' ' . ($row['last_name'] ?? '')),
                        'email' => $email,
                        'program_name' => $program->program_name,
                        'year_level' => $yearLevel,
                        'contact_number' => (string) ($row['contact'] ?? ''),
                        'date_of_birth' => $this->parseDate($row['date_of_birth']),
                        'sex' => $sex,
                    ];
                } catch (\Exception $e) {
                    Log::error('Failed to save student', [
                        'row' => $index + 2,
                        'student_id' => $studentId,
                        'email' => $email,
                        'error' => $e->getMessage()
                    ]);
                    $skipped++;
                }
            }
        });

        return [
            'studentsForResponse' => $studentsForResponse,
            'added' => $added,
            'skipped' => $skipped,
        ];
    }

    private function parseDate($date)
    {
        if (!$date) {
            return null;
        }

        try {
            if (is_numeric($date)) {
                return Carbon::createFromTimestamp(Carbon::create(1899, 12, 30)->timestamp + ($date * 86400))->format('Y-m-d');
            }

            if (preg_match('/^=DATE\((\d+),\s*(\d+),\s*(\d+)\)$/i', $date, $matches)) {
                $year = (int)$matches[1];
                $month = (int)$matches[2];
                $day = (int)$matches[3];
                return Carbon::create($year, $month, $day)->format('Y-m-d');
            }

            return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            Log::warning('Failed to parse date', ['date' => $date, 'error' => $e->getMessage()]);
            return null;
        }
    }

    public function checkStudentId(Collection $rows, string $studentId): bool
    {
        return $rows->contains(function ($row) use ($studentId) {
            return isset($row['id']) && (string) $row['id'] === $studentId;
        });
    }

    public function checkStudentIdsBatch(Collection $rows, array $studentIds): array
    {
        $results = [];

        foreach ($studentIds as $studentId) {
            $studentId = (string) $studentId;
            $isEligible = $rows->contains(function ($row) use ($studentId) {
                return isset($row['id']) && (string) $row['id'] === $studentId;
            });
            $results[$studentId] = $isEligible;
        }

        return $results;
    }

    public function getStudentById(Collection $rows, string $studentId): ?array
    {
        $studentRow = $rows->firstWhere('id', $studentId);

        if (!$studentRow) {
            return null;
        }

        return [
            'id' => (string) ($studentRow['id'] ?? ''),
            'first_name' => $studentRow['first_name'] ?? '',
            'middle_name' => $studentRow['middle_name'] ?? '',
            'last_name' => $studentRow['last_name'] ?? '',
            'email' => $studentRow['email'] ?? '',
            'program' => $studentRow['program'] ?? '',
            'year_level' => $studentRow['year'] ?? '',
            'contact_number' => (string) ($studentRow['contact'] ?? ''),
            'date_of_birth' => $studentRow['date_of_birth'] ?? '',
        ];
    }
}
