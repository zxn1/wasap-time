<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
