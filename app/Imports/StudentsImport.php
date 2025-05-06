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

    /**
     * Process the Excel file and import students into the database in batches.
     *
     * @param  \Illuminate\Support\Collection  $rows
     * @return array
     */
    public function collection(Collection $rows)
    {
        $programs = Program::all()->keyBy('program_name');
        $existingStudents = Student::all()->keyBy('email');
        $studentsForResponse = [];
        $skipped = 0;
        $added = 0;

        $rows->chunk(100)->each(function ($chunk) use ($programs, &$existingStudents, &$studentsForResponse, &$skipped, &$added) {
            $studentsToInsert = [];

            foreach ($chunk as $index => $row) {
                $email = $row['email'] ?? '';
                $programCode = trim($row['program'] ?? ''); // Trim spaces from program code
                $id = $row['id'] ?? null;

                if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL) || empty($id) || !is_numeric($id)) {
                    Log::warning('Skipped row due to invalid data', ['row' => $index + 2, 'id' => $id, 'email' => $email]);
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

                $existingStudent = $existingStudents[$email] ?? null;
                if ($existingStudent && $existingStudent->id != $id) {
                    Log::warning('Skipped row due to duplicate email with different ID', [
                        'row' => $index + 2,
                        'email' => $email,
                        'existing_id' => $existingStudent->id,
                        'new_id' => $id
                    ]);
                    $skipped++;
                    continue;
                }

                $studentData = [
                    'id' => $row['id'],
                    'first_name' => $row['first_name'],
                    'middle_name' => $row['middle_name'] ?? null,
                    'last_name' => $row['last_name'],
                    'email' => $row['email'],
                    'program_id' => $program->program_id,
                    'year_level' => $row['year'],
                    'contact_number' => (string) ($row['contact'] ?? ''),
                    'date_of_birth' => $this->parseDate($row['date_of_birth']),
                    'sex' => $row['sex'] ?? '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $student = Student::updateOrCreate(
                    ['id' => $row['id']],
                    $studentData
                );

                if ($student->wasRecentlyCreated) {
                    $added++;
                } else {
                    Log::info('Updated existing student', ['id' => $id, 'email' => $email]);
                }

                $existingStudents[$email] = $student;

                $studentsForResponse[] = [
                    'id' => (string) $row['id'],
                    'name' => trim(($row['first_name'] ?? '') . ' ' . ($row['middle_name'] ?? '') . ' ' . ($row['last_name'] ?? '')),
                    'email' => $row['email'],
                    'program_name' => $program->program_name,
                    'year_level' => $row['year'] ?? '',
                    'contact_number' => (string) ($row['contact'] ?? ''),
                    'date_of_birth' => $this->parseDate($row['date_of_birth']),
                    'sex' => $row['sex'] ?? '',
                ];
            }
        });

        return [
            'studentsForResponse' => $studentsForResponse,
            'added' => $added,
            'skipped' => $skipped,
        ];
    }

    /**
     * Parse the date of birth from the Excel format to Y-m-d.
     *
     * @param  mixed  $date
     * @return string|null
     */
    private function parseDate($date)
    {
        if (!$date) {
            return null;
        }

        try {
            if (is_numeric($date)) {
                // Convert Excel serial date to Carbon date (Excel's base date is 1900-01-01)
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

    /**
     * Check if a student ID exists in the Excel rows.
     *
     * @param  \Illuminate\Support\Collection  $rows
     * @param  string  $studentId
     * @return bool
     */
    public function checkStudentId(Collection $rows, string $studentId): bool
    {
        return $rows->contains(function ($row) use ($studentId) {
            return isset($row['id']) && (string) $row['id'] === $studentId;
        });
    }

    /**
     * Check multiple student IDs in batch.
     *
     * @param  \Illuminate\Support\Collection  $rows
     * @param  array  $studentIds
     * @return array
     */
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

    /**
     * Fetch student details by ID.
     *
     * @param  \Illuminate\Support\Collection  $rows
     * @param  string  $studentId
     * @return array|null
     */
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
