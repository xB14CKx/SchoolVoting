<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Program;

class Student extends Model
{
    /* -----------------------------------------------------------------
     |  Table & PK settings
     |------------------------------------------------------------------*/
    protected $table      = 'students';   // explicit, but optional if you keep default
    protected $primaryKey = 'student_id';
    public $incrementing = false; // If it's not auto-incrementing

    // `id` is AUTO_INCREMENT UNSIGNED BIGINT, so leave $incrementing true
    // (default) and no need to override $keyType unless you like the clarity:
    protected $keyType    = 'int';

    /* -----------------------------------------------------------------
     |  Mass-assignable attributes
     |------------------------------------------------------------------*/
    protected $fillable = [
        'student_id', // Added to allow mass assignment
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'program_id',
        'year_level',
        'contact_number',
        'image',
        'date_of_birth',
        'sex',
    ];

    /* -----------------------------------------------------------------
     |  Relationships
     |------------------------------------------------------------------*/

    /**
     * The user account that belongs to this student.
     * (NULL if the student hasn’t registered yet.)
     */
    public function user()
    {
        // FK is users.student_id → students.student_id (not students.id)
        return $this->hasOne(User::class, 'student_id', 'student_id');
    }

    /**
     * Academic program the student is enrolled in.
     */
    public function program()
    {
        // FK is students.program_id → programs.program_id
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }
}
