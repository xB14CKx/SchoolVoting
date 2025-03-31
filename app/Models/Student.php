<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false; // ID is not auto-incrementing
    protected $keyType = 'int'; // ID is an integer

    protected $fillable = [
        'id',
        'first_name',
        'middle_initial',
        'last_name',
        'email',
        'program',
        'year',
        'contact_number',
        'date_of_birth',
    ];

    /**
     * Accessor to map the 'year' column to 'year_level'.
     *
     * @return int
     */
    public function getYearLevelAttribute()
    {
        return $this->year;
    }
}