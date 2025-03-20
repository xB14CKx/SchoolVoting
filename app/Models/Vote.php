<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['election_id', 'candidate_id', 'user_id'];

    // A vote belongs to an election
    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }

    // A vote belongs to a candidate
    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    // A vote belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}