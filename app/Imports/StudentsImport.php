<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentsImport implements ToCollection
{
    /**
     * Process the Excel file into a collection of student IDs.
     *
     * @param  \Illuminate\Support\Collection  $rows
     * @return \Illuminate\Support\Collection
     */
    public function collection(Collection $rows)
    {
        // Skip the header row (first row) and extract the first column (student IDs)
        return $rows->slice(1)->map(function ($row) {
            return isset($row[0]) ? (string) $row[0] : null;
        })->filter()->values();
    }

    /**
     * Check if a student ID exists in the Excel rows (skipping the first row).
     *
     * @param  \Illuminate\Support\Collection  $rows
     * @param  string  $studentId
     * @return bool
     */
    public function checkStudentId(Collection $rows, string $studentId): bool
    {
        // Skip the header row (first row) and check for the student ID in the first column
        return $rows->slice(1)->contains(function ($row) use ($studentId) {
            return isset($row[0]) && (string) $row[0] === $studentId;
        });
    }

    /**
     * Check multiple student IDs in batch (skipping the first row).
     *
     * @param  \Illuminate\Support\Collection  $rows
     * @param  array  $studentIds
     * @return array
     */
    public function checkStudentIdsBatch(Collection $rows, array $studentIds): array
    {
        // Skip the header row (first row)
        $dataRows = $rows->slice(1);

        // Initialize the result array
        $results = [];

        // Iterate through the provided student IDs
        foreach ($studentIds as $studentId) {
            $studentId = (string) $studentId; // Ensure the ID is a string
            $isEligible = $dataRows->contains(function ($row) use ($studentId) {
                return isset($row[0]) && (string) $row[0] === $studentId;
            });
            $results[$studentId] = $isEligible;
        }

        return $results;
    }

    /**
     * Fetch student details by ID (skipping the first row).
     *
     * @param  \Illuminate\Support\Collection  $rows
     * @param  string  $studentId
     * @return array|null
     */
    public function getStudentById(Collection $rows, string $studentId): ?array
    {
        // Skip the header row (first row)
        $studentRow = $rows->slice(1)->firstWhere(function ($row) use ($studentId) {
            return isset($row[0]) && (string) $row[0] === $studentId;
        });

        if (!$studentRow) {
            return null;
        }

        // Map the row data to an associative array based on the Excel structure
        return [
            'student_id' => (string) ($studentRow[0] ?? ''),
            'first_name' => $studentRow[1] ?? '',
            'middle_initial' => $studentRow[2] ?? '',
            'last_name' => $studentRow[3] ?? '',
            'email' => $studentRow[4] ?? '',
            'program' => $studentRow[5] ?? '',
            'year_level' => $studentRow[6] ?? '',
            'contact_number' => (string) ($studentRow[7] ?? ''),
            'date_of_birth' => $studentRow[8] ?? '',
        ];
    }
}