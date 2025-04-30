<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'programs';
    protected $primaryKey = 'program_id';
    public $incrementing = true; // Auto-incrementing
    protected $keyType = 'int';

    protected $fillable = [
        'program_id',
        'program_name',
    ];

    /**
     * Get the students associated with this program.
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'program_id', 'program_id');
    }
}
