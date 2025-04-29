<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    protected $primaryKey = 'election_id';
    protected $fillable = ['year', 'status'];

    // An election can have many candidates
    public function candidates()
    {
        return $this->belongsToMany(Candidate::class, 'election_candidates', 'election_id', 'candidate_id')
                    ->withTimestamps();
    }

    // An election can have many votes
    public function votes()
    {
        return $this->hasMany(Vote::class, 'election_id');
    }

    // An election can have many results
    public function electionResults()
    {
        return $this->hasMany(ElectionResult::class, 'election_id');
    }
}
