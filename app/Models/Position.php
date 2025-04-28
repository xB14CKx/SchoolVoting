<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name'];

    /**
     * Get the candidates running for this position.
     *
     * @return HasMany
     */
    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class, 'position_id', 'position_id');
    }
}