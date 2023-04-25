<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\directChatt;
use App\Models\chatmessage;

class DirectChat extends Component
{
    public $session, $chatID = null, $newChat = false, $messageInput = '', $messageChats = [], $limiterChat = 15;

    public function mount()
    {
        $res1 = directChatt::where('from_id', session('wasap_sess'))->where('to_id', $this->session);
        $res2 = directChatt::where('to_id', session('wasap_sess'))->where('from_id', $this->session);
        if($res1->exists())
        {
            $this->chatID = $res1->first()->chatid;
        } elseif ($res2->exists())
        {
            $this->chatID = $res2->first()->chatid;
        } else {
            $length = 112; // 124 characters since each byte is represented by 2 hexadecimal digits
            $random_bytes = random_bytes($length);
            $random_string = base64_encode(substr(bin2hex($random_bytes), 0, $length));
            
            $this->chatID = $random_string;
            $this->newChat = true;
        }

        //get all message
        $this->messageChats = chatmessage::latest('id')->where('chat_id', $this->chatID)->limit($this->limiterChat)->get()->reverse();
    }

    public function viewMoreChat()
    {
        $this->limiterChat = $this->limiterChat + 5;
    }

    public function sentMessage()
    {
        if($this->newChat == true)
        {
            //create new chatbox
            $chatbox = new directChatt;
            $chatbox->from_id = session('wasap_sess');
            $chatbox->to_id = $this->session;
            $chatbox->chatid = $this->chatID;
            $chatbox->save();
        }

        //assign new message to chatbox or use existed chatbox
        $chatMessage = new chatmessage;
        $chatMessage->chat_id = $this->chatID;
        $chatMessage->from_id = session('wasap_sess');
        $chatMessage->chat_message = $this->messageInput;
        $chatMessage->save();

        $this->messageInput = '';
    }

    public function getMessage()
    {
        $this->messageChats = chatmessage::latest('id')->where('chat_id', $this->chatID)->limit($this->limiterChat)->get()->reverse();
    }

    public function render()
    {
        return view('livewire.direct-chat');
    }
}
