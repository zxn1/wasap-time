<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chatmessage extends Model
{
    use HasFactory;
    protected $table = 'chatmessages';
    protected $fillable = [
        'chat_id',
        'from_id',
        'checkhmac',
        'chat_message',
        'created_at'
    ];

    public function randSessions()
    {
        return $this->hasOne(randSessions::class, 'session_id', 'from_id');
    }
}
