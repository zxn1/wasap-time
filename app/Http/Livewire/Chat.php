<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Chatting;

class Chat extends Component
{
    public $session = null, $chats;
    protected $listeners = ['putSession' => 'setSession'];

    public function setSession($sessionVal)
    {
        $this->session = $sessionVal;
    }

    public function render()
    {
        $chats = Chatting::limit(10)->get();
        return view('livewire.chat');
    }
}
