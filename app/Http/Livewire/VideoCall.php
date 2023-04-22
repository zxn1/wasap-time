<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\rtcSignalling;
use Carbon\Carbon;

class VideoCall extends Component
{
    public $from_id, $to_id, $rtc_id, $sdp_answer;

    protected $listeners = [
        'emitSDP' => 'insertSDPOffer',
        'getSDP' => 'getSDPAnswer'
    ];

    /*
    ---------------REFERENCES ------------------
        'from_id',
        'to_id',
        'sdp_offer',
        'sdp_answer',
        'status'
        --------------------------------------
    */
    
    public function insertSDPOffer($offer)
    {
        $rtc = rtcSignalling::where('from_id', $this->from_id)->orderBy('id', 'desc')->first();
        if($rtc != null)
        {
            $this->rtc_id = $rtc->id;
            $signalling = rtcSignalling::find($this->rtc_id);
            $signalling->to_id = $this->to_id;
            $signalling->sdp_offer = $offer;
            $signalling->status = 'ringing';
            $signalling->created_at = Carbon::now();
            $signalling->save();
        } else {
            $signalling = new rtcSignalling;
            $signalling->from_id = $this->from_id;
            $signalling->to_id = $this->to_id;
            $signalling->sdp_offer = $offer;
            $signalling->status = 'ringing';
            $signalling->save();
            $this->rtc_id = $signalling->id;
        }
    }

    public function closeCall()
    {
        $rtc = rtcSignalling::find($this->rtc_id);
        if($rtc)
        {
            $rtc->delete();
        }
        
        return redirect('/list');
    }

    public function getSDPAnswer()
    {
        $result = rtcSignalling::where('id', $this->rtc_id)->select('sdp_answer')->first();
        if (isset($result->sdp_answer)) {
            $this->sdp_answer = $result->sdp_answer;
            $this->emit('getSDP', $this->sdp_answer);
        } else {
            sleep(1);
            $this->emit('getSDP', null);
        }
    }

    public function mount()
    {
        $this->from_id = request()->input('from_id');
        $this->to_id = request()->input('to_id');
    }
    
    public function render()
    {
        return view('livewire.video-call');
    }
}
