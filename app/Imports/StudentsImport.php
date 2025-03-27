<?php

namespace App\Imports;

use App\Models\Student; // Assuming you have a Student model
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // To treat the first row as headers
use Carbon\Carbon; // For date parsing

class StudentsImport implements ToCollection, WithHeadingRow
{
    /**
     * Process the Excel file and import students into the database.
     *
     * @param  \Illuminate\Support\Collection  $rows
     * @return void
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Map the Excel row to the database columns
            Student::updateOrCreate(
                ['id' => $row['id']], // Use the ID as the unique key
                [
                    'id' => $row['id'],
                    'first_name' => $row['first_name'],
                    'middle_initial' => $row['mi'] ?? null, // M.I. might be empty
                    'last_name' => $row['last_name'],
                    'email' => $row['email'],
                    'program' => $row['program'],
                    'year' => $row['year'],
                    'contact_number' => (string) $row['contact_'], // Ensure it's a string
                    'date_of_birth' => $this->parseDate($row['date_of_birth']),
                ]
            );
        }
    }

    /**
     * Parse the date of birth from the Excel format (DD/MM/YYYY) to Y-m-d.
     *
     * @param  string  $date
     * @return string|null
     */
    private function parseDate($date)
    {
        if (!$date) {
            return null;
        }

        // The Excel date is in DD/MM/YYYY format (e.g., 07/07/2004)
        try {
            return Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            // If parsing fails, return null or handle the error as needed
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
            'student_id' => (string) ($studentRow['id'] ?? ''),
            'first_name' => $studentRow['first_name'] ?? '',
            'middle_initial' => $studentRow['mi'] ?? '',
            'last_name' => $studentRow['last_name'] ?? '',
            'email' => $studentRow['email'] ?? '',
            'program' => $studentRow['program'] ?? '',
            'year_level' => $studentRow['year'] ?? '',
            'contact_number' => (string) ($studentRow['contact_'] ?? ''),
            'date_of_birth' => $studentRow['date_of_birth'] ?? '',
        ];
    }
}