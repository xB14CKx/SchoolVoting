<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // Map the rows to a collection of student IDs
        return $rows->pluck('student_id')->map(function ($id) {
            // Ensure the student ID is treated as a string (Excel might treat it as a number)
            return (string) $id;
        });
    }
}