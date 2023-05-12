<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\randSessions;
use App\Models\chatmessage;

class directChatt extends Model
{
    use HasFactory;
    protected $table = 'direct_chats';
    protected $fillable = [
        'from_id',
        'to_id',
        'chatid',
        'created_at'
    ];

    public function fromName()
    {
        return $this->hasOne(randSessions::class, 'session_id', 'from_id');
    }

    public function toName()
    {
        return $this->hasOne(randSessions::class, 'session_id', 'to_id');
    }

    public function getLatestMessage()
    {
        return $this->hasMany(chatmessage::class, 'chat_id', 'chatid')->orderBy('created_at', 'desc')
        ->limit(1);
    }

    public function getCountMessage()
    {
        return $this->hasMany(chatmessage::class, 'chat_id', 'chatid')->orderBy('created_at', 'desc')
        ->where('have_read', 'received')->where('from_id', '<>', session('wasap_sess'));
    }
}
