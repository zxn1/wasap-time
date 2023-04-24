<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\directChatt;

class Conversation extends Component
{
    public $directChat = [];
    public function mount()
    {
        $val1 = directChatt::where('from_id', session('wasap_sess'))->get();
        $val2 = directChatt::where('to_id', session('wasap_sess'))->get();
        $this->directChat = $val1->concat($val2);
    }

    public function render()
    {
        return view('livewire.conversation');
    }
}
