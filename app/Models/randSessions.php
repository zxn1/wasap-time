<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class randSessions extends Model
{
    use HasFactory;
    protected $table = 'rand_sessions';
    protected $fillable = [
        'session_id',
        'name'
    ];
    protected $primaryKey = 'session_id';
    public $incrementing = false;
}
