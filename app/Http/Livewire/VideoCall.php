<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\rtcSignalling;

class VideoCall extends Component
{
    public $from_id, $to_id;
    
    public function render()
    {
        $this->from_id = request()->input('from_id');
        $this->to_id = request()->input('to_id');

        return view('livewire.video-call');
    }
}
