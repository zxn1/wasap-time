<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\rtcSignalling;
use App\Models\randSessions;
use Carbon\Carbon;
use App\Http\Livewire\lastActivity;

class IncomingCall extends Component
{
    public $cur_session, $showCall = 1, $nameCaller, $rtc_id;

    public function getIncomingCall()
    {
        $result = rtcSignalling::where('to_id', $this->cur_session)->where('status', 'ringing')->first();
        if(isset($result))
        {
            $this->rtc_id = $result->id;
            if($result->created_at->diffInMinutes(Carbon::now()) <= 2)
            {
                $this->showCall = 2;
            } else {
                $this->showCall = 1;
            }
        } else {
            $this->showCall = 1;
        }
    }

    public function declineCall()
    {
        lastActivity::lastAcitivityUpdate();
        $rtcSignalling = rtcSignalling::find($this->rtc_id);

        if ($rtcSignalling) {
            $rtcSignalling->delete();
        }
    }

    public function mount()
    {
        $this->cur_session = session('wasap_sess');
    }

    public function render()
    {
        $result = rtcSignalling::where('to_id', $this->cur_session)->where('status', 'ringing')->first();
        if(isset($result))
        {
            if($result->created_at->diffInMinutes(Carbon::now()) <= 2)
            {
                $this->nameCaller = randSessions::where('session_id', $result->from_id)->first()->name;
                $this->showCall = 2;
            } else {
                $this->showCall = 1;
            }
        } else {
            $this->showCall = 1;
        }
        return view('livewire.incoming-call');
    }
}
