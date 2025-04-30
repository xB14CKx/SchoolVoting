<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'programs'; // Explicitly set the table name
    protected $primaryKey = 'program_id'; // Match the column name from the database
    public $incrementing = true; // Auto-incrementing by default
    protected $keyType = 'int';

    protected $fillable = [
        'program_id',
        'program_name',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the students associated with this program.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'program_id', 'program_id');
    }
}
