<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'year_level',
        'program_id',
        'partylist_id',
        'position_id',
        'image',
        'platform',
        'program',
    ];

    /**
     * Get the program that the candidate belongs to.
     *
     * @return BelongsTo
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id', 'program_id');
    }

    /**
     * Get the partylist that the candidate is affiliated with.
     *
     * @return BelongsTo
     */
    public function partylist(): BelongsTo
    {
        return $this->belongsTo(Partylist::class, 'partylist_id', 'partylist_id');
    }

    /**
     * Get the position that the candidate is running for.
     *
     * @return BelongsTo
     */
    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id', 'position_id');
    }

    /**
     * Get the elections that the candidate is participating in.
     *
     * @return BelongsToMany
     */
    public function elections(): BelongsToMany
    {
        return $this->belongsToMany(Election::class, 'election_candidates', 'candidate_id', 'election_id')
            ->withTimestamps();
    }

    /**
     * Get the votes cast for the candidate.
     *
     * @return HasMany
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'candidate_id');
    }

    /**
     * Get the election results for the candidate.
     *
     * @return HasMany
     */
    public function electionResults(): HasMany
    {
        return $this->hasMany(ElectionResult::class, 'candidate_id');
    }
}
