<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ElectionCandidate extends Pivot
{
    protected $table = 'election_candidates';
}