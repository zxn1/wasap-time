<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\randSessions;

class Friendlist extends Component
{
    public $search = '', $lists = [];
    public function render()
    {
        $this->lists = randSessions::where('name', 'like', '%' . $this->search . '%')->limit(10)->get();
        return view('livewire.friendlist');
    }
}
