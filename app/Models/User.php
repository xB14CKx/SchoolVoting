<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Student;   // <-- make sure the class is imported
use App\Models\Vote;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'student_id',     // <-- NEW
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /* -----------------------------------------------------------------
     |  Relationships
     |------------------------------------------------------------------*/

    /**
     * Student record this user belongs to (nullable for admins/staff).
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Votes cast by this user.
     */
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /* -----------------------------------------------------------------
     |  Convenience helpers
     |------------------------------------------------------------------*/

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }
}
