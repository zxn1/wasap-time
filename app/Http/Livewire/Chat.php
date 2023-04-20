<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Chatting;

class Chat extends Component
{
    public $session = null, $chats = [], $chatInput = '';
    protected $listeners = ['putSession' => 'setSession'];

    public function setSession($sessionVal)
    {
        $this->session = $sessionVal;
    }

    public function getMessage()
    {
        $this->chats = Chatting::latest('id')->limit(20)->get()->reverse();
    }

    public function sendMessage()
    {
        $chatt = new Chatting;
        $chatt->messages = $this->chatInput;
        $chatt->from_id = $this->session;
        $chatt->save();
        $this->chatInput = '';
    }

    public function render()
    {
        $this->chats = Chatting::latest('id')->limit(20)->get()->reverse();
        return view('livewire.chat');
    }
}
