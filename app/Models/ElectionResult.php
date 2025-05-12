<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectionResult extends Model
{
    protected $table = 'election_results';

    protected $fillable = ['election_id', 'candidate_id', 'votes'];

    // An election result belongs to an election
    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }

    // An election result belongs to a candidate
    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    // An election result belongs to a position
    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }
}
