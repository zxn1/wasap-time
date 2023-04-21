<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rtcSignalling extends Model
{
    use HasFactory;
    protected $table = 'rtc_signallings';
    protected $fillable = [
        'from_id',
        'to_id',
        'sdp_offer',
        'sdp_answer',
        'status'
    ];
}
