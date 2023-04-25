<?php

namespace App\Http\Livewire;

use App\Models\randSessions;
use Carbon\Carbon;

class LastActivity
{
    public function lastAcitivityUpdate()
    {
        $lastActivty = randSessions::find(session('wasap_sess'));
        $lastActivty->last_activity = Carbon::now();
        $lastActivty->save();
    }

    public function checkSession()
    {
        if(randSessions::where('session_id', session('wasap_sess'))->exists())
            return true;
        return false;
    }

    public function deactivate()
    {
        $lastActivty = randSessions::find(session('wasap_sess'));
        if($lastActivty != null)
        {
            $lastActivty->last_activity = '';
            $lastActivty->save();
        }
    }
}
