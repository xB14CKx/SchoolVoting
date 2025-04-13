<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'year_level',
        'program_id',
        'partylist_id',
        'position_id',
        'image',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }

    public function partylist()
    {
        return $this->belongsTo(Partylist::class, 'partylist_id', 'partylist_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }
}
