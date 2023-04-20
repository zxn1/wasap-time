<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Chatting;

class Chat extends Component
{
    public $session = null, $chats = [], $chatInput = '', $limiter = 20;
    protected $listeners = ['putSession' => 'setSession'];

    public function setSession($sessionVal)
    {
        $this->session = $sessionVal;
    }

    public function getMessage()
    {
        $this->chats = Chatting::latest('id')->limit($this->limiter)->get()->reverse();
    }

    public function addLimiter()
    {
        $this->limiter = $this->limiter + 10;
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
        $this->chats = Chatting::latest('id')->limit($this->limiter)->get()->reverse();
        return view('livewire.chat');
    }
}
