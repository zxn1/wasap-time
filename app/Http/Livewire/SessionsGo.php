<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\randSessions;

class SessionsGo extends Component
{
    public $ses, $name, $chat = false;

    public function setSession()
    {
        $sess = new randSessions;
        $sess->session_id = $this->ses;
        $sess->name = $this->name;
        $sess->save();
        $this->emit('putSession', $this->ses);
        session(['wasap_sess' => $this->ses]);
        $this->chat = true;
    }

    public function removeSession()
    {
        $this->ses = '';
        session(['wasap_sess' => '']);
        $this->chat = false;
    }

    public function render()
    {
        if(session('wasap_sess') == '' || session('wasap_sess') == null)
        {
            $random_bytes = random_bytes(32); // generate 32 random bytes
            $random_string = bin2hex($random_bytes); // convert bytes to hex string
            $this->ses = $random_string;
        }
        return view('livewire.sessions-go');
    }
}
