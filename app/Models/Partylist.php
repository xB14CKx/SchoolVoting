<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partylist extends Model
{
    protected $table = 'partylists'; // <-- Make sure this matches your table name in MySQL

    protected $primaryKey = 'partylist_id';

    public $timestamps = false; // If you don’t have created_at / updated_at columns
}
