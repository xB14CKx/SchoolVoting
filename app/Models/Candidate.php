<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = ['first_name', 'last_name', 'year_level', 'program', 'image'];

    // A candidate can participate in many elections
    public function elections()
    {
        return $this->belongsToMany(Election::class, 'election_candidates', 'candidate_id', 'election_id')
                    ->withTimestamps();
    }

    // A candidate can have many votes
    public function votes()
    {
        return $this->hasMany(Vote::class, 'candidate_id');
    }

    // A candidate can have many election results
    public function electionResults()
    {
        return $this->hasMany(ElectionResult::class, 'candidate_id');
    }

    public function candidates()
{
    return $this->belongsToMany(Candidate::class, 'election_candidates', 'election_id', 'candidate_id')
                ->withTimestamps()
                ->using(ElectionCandidate::class);
}
}