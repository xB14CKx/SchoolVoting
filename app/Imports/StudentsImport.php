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
}