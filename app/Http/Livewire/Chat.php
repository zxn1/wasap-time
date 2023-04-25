<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Chatting;
use App\Models\randSessions;
use Carbon\Carbon;
use App\Http\Livewire\lastActivity;

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
        lastActivity::lastAcitivityUpdate();
        $this->chats = Chatting::latest('id')->limit($this->limiter)->get()->reverse();
    }

    public function addLimiter()
    {
        lastActivity::lastAcitivityUpdate();
        $this->limiter = $this->limiter + 10;
    }

    public function sendMessage()
    {
        lastActivity::lastAcitivityUpdate();
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
