<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\randSessions;

class Chatting extends Model
{
    use HasFactory;
    protected $table = 'chattings';
    protected $fillable = [
        'from_id',
        'messages',
        'created_at'
    ];

    public function randSessions()
    {
        return $this->hasOne(randSessions::class, 'session_id', 'from_id');
    }
}
