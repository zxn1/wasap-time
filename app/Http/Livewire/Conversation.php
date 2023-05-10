<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\directChatt;

use phpseclib\Crypt\RSA;
use Illuminate\Support\Facades\Storage;

class Conversation extends Component
{
    public $directChat = [], $search = '';

    public function mount()
    {
        //create RSA instance
        $rsa = new RSA();

        //combined all conversation
        $val1 = directChatt::where('from_id', session('wasap_sess'))->get();
        $val2 = directChatt::where('to_id', session('wasap_sess'))->get();
        $this->directChat = $val1->concat($val2);

        //traverse all chat and decrypt
        for($i = 0; $i < count($this->directChat); $i++)
        {
            $session_id = $this->directChat[$i]->getLatestMessage[0]->from_id;

            //get latest message publicKey
            if(Storage::exists('public_' . $session_id . '.key'))
            {
                $publicKey = Storage::get('public_' . $session_id . '.key');

                //load public key to decrypt
                $rsa->loadKey($publicKey);

                $this->directChat[$i]->getLatestMessage[0]->chat_message = $rsa->decrypt($this->directChat[$i]->getLatestMessage[0]->chat_message);
            }
        }
    }

    public function render()
    {
        return view('livewire.conversation');
    }
}
