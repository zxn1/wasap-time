<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\randSessions;
use App\Http\Livewire\lastActivity;
use Carbon\Carbon;

class Friendlist extends Component
{
    public $search = '', $lists = [], $onlineList = [], $deactivated = [];

    public function render()
    {
        $current_time = Carbon::now();
        $earlier_time = $current_time->subMinutes(1);

        $last_activity = new LastActivity();
        $last_activity->lastAcitivityUpdate();
        $this->lists = randSessions::where('name', 'like', '%' . $this->search . '%')->where('session_id', '<>', session('wasap_sess'))->where('last_activity', '<', $earlier_time)->where('last_activity', '<>', '')->limit(10)->get();
        $this->onlineList = randSessions::where('name', 'like', '%' . $this->search . '%')->where('session_id', '<>', session('wasap_sess'))->where('last_activity', '>=', $earlier_time)->where('last_activity', '<>', '')->limit(5)->get();
        $this->deactivated = randSessions::where('name', 'like', '%' . $this->search . '%')->where('session_id', '<>', session('wasap_sess'))->where('last_activity', '=', '')->limit(5)->get();
        return view('livewire.friendlist');
    }
}
