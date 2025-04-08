<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Program extends Model
{
    protected $table = 'programs';
    protected $primaryKey = 'program_id';
    public $timestamps = false; // if you’re not using created_at and updated_at
}
