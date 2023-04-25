<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\rtcSignalling;

class AcceptCall extends Component
{
    public $rtc_id, $endCall = false;

    protected $listeners = [
        'getSDPSender' => 'getSenderSDP',
        'updateAnswer' => 'updateSDPAnswer'];

    public function getSenderSDP()
    {
        $sdp = rtcSignalling::where('to_id', session('wasap_sess'))->where('status', '<>', 'end')->latest()->first();
        $this->rtc_id = $sdp->id;
        sleep(2);
        $this->emit('getSDPSender', $sdp);
    }

    public function getEndCall()
    {
        $rtc = rtcSignalling::where('to_id', session('wasap_sess'))->orderBy('id', 'desc')->first();
        if($rtc != null)
        {
            $this->endCall = false;
        } else {
            $this->endCall = true;
        }
    }

    public function updateSDPAnswer($sdpAnswer)
    {
        $rtcSignalling = rtcSignalling::find($this->rtc_id);
        $rtcSignalling->sdp_answer = $sdpAnswer;
        $rtcSignalling->save();
    }

    public function closeCall()
    {
        $rtcSignalling = rtcSignalling::find($this->rtc_id);

        if ($rtcSignalling) {
            $rtcSignalling->delete();
        }
        
        return redirect('/list');
    }

    public function render()
    {
        return view('livewire.accept-call');
    }
}
