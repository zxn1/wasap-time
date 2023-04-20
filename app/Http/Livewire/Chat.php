<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Chat extends Component
{
    public $session = null;
    protected $listeners = ['putSession' => 'setSession'];

    public function setSession($sessionVal)
    {
        $this->session = $sessionVal;
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
