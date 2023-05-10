<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\directChatt;
use App\Models\chatmessage;
use App\Http\Livewire\lastActivity;

use Illuminate\Encryption\Encrypter;
use phpseclib\Crypt\RSA;
use Illuminate\Support\Facades\Storage;

class DirectChat extends Component
{
    public  $session, 
            $chatID = null, 
            $newChat = false, 
            $messageInput = '', 
            $messageChats = [], 
            $limiterChat = 15,
            $myPublicKey = null,
            $memberPublicKey = null;
    
    //privacy protection - encryption
    protected   $encrypter = null, 
                $rsa = null, 
                $rsa_self = null, 
                $rsa_member = null;

    public function __construct()
    {
        parent::__construct();

        //initialize the encrypter
        $secretHMAC = hex2bin(env("HMEC_SECRET_KEY"));
        $this->encrypter = new Encrypter($secretHMAC, 'AES-256-CBC');

        //create rsa encryption
        $this->rsa = new RSA(); //focus on sending message
        $this->rsa_self = new RSA(); //focus on self decryption
        $this->rsa_member = new RSA(); //focus on member decryption

        //get own publicKey
        if(Storage::exists('public_' . session('wasap_sess') . '.key'))
        {
            $this->myPublicKey = Storage::get('public_' . session('wasap_sess') . '.key');

            //load public key to decrypt
            $this->rsa_self->loadKey($this->myPublicKey);
        }
    }

    public function mount()
    {    
        //fetching data
        $res1 = directChatt::where('from_id', session('wasap_sess'))->where('to_id', $this->session);
        $res2 = directChatt::where('to_id', session('wasap_sess'))->where('from_id', $this->session);
        if($res1->exists())
        {
            $get1 = $res1->first();
            $this->chatID = $get1->chatid;
            $this->memberPublicKey = $get1->to_id;
        } elseif ($res2->exists())
        {
            $get2 = $res2->first();
            $this->chatID = $get2->chatid;
            $this->memberPublicKey = $get2->from_id;
        } else {
            $length = 112; // 124 characters since each byte is represented by 2 hexadecimal digits
            $random_bytes = random_bytes($length);
            $random_string = base64_encode(substr(bin2hex($random_bytes), 0, $length));
            
            $this->chatID = $random_string;
            $this->newChat = true;
        }

        //get all message
        $this->messageChats = chatmessage::latest('id')->where('chat_id', $this->chatID)->limit($this->limiterChat)->get()->reverse();

        //get member public key
        if(Storage::exists('public_' . $this->memberPublicKey . '.key'))
        {
            $this->memberPublicKey = Storage::get('public_' . $this->memberPublicKey . '.key');
        }

        //update last activity
        $last_activity = new LastActivity();
        $last_activity->lastAcitivityUpdate();
    }

    public function viewMoreChat()
    {
        $this->limiterChat = $this->limiterChat + 5;

        //update last activity
        $last_activity = new LastActivity();
        $last_activity->lastAcitivityUpdate();
    }

    public function sentMessage()
    {
        //if message input is empty
        if($this->messageInput == '' || empty($this->messageInput))
        {
            return 0;
        }
        
        if($this->newChat == true)
        {
            //create new chatbox
            $chatbox = new directChatt;
            $chatbox->from_id = session('wasap_sess');
            $chatbox->to_id = $this->session;
            $chatbox->chatid = $this->chatID;
            $chatbox->save();

            //set MemberPrivateKey
            $this->memberPublicKey = $this->session;
        }

        //RSA encryption
        $privateKey = null;
        if(Storage::exists('private_' . session('wasap_sess') . '.key'))
        {
            $privateKey = Storage::get('private_' . session('wasap_sess') . '.key');
        } else {
            $keyPair = $this->rsa->createKey();
            $publicKey = $keyPair['publickey'];
            $privateKey = $keyPair['privatekey'];
            
            //if not exists - create both private and public key file
            Storage::put('public_' . session('wasap_sess') . '.key', $publicKey);
            Storage::put('private_' . session('wasap_sess') . '.key', $privateKey);
        }
        //purify the message input
        $this->messageInput = htmlspecialchars($this->messageInput);

        //do encryption
        $this->rsa->loadKey($privateKey);
        $encrypted_message = $this->rsa->encrypt($this->messageInput);

        //ensure integrity and authoricity
        $hmac = hash_hmac('sha256', $encrypted_message, $this->encrypter->getKey());

        //assign new message to chatbox or use existed chatbox
        $chatMessage = new chatmessage;
        $chatMessage->chat_id = $this->chatID;
        $chatMessage->from_id = session('wasap_sess');
        $chatMessage->checkhmac = $hmac;
        $chatMessage->chat_message = $encrypted_message;
        
        if($chatMessage->save())
        {        
            if($this->newChat == true)
            {
                $this->newChat = false;
            }

            //update last activity
            $last_activity = new LastActivity();
            $last_activity->lastAcitivityUpdate();
        }

        $this->messageInput = '';
    }

    //this function for checking for update - polling
    public function getMessage()
    {
        if(!empty($this->memberPublicKey) && $this->memberPublicKey == $this->session)
        {
            //get the member public key
            if(Storage::exists('public_' . $this->memberPublicKey . '.key'))
            {
                $this->memberPublicKey = Storage::get('public_' . $this->memberPublicKey . '.key');
            }

            //load public key to decrypt
            $this->rsa_member->loadKey($this->memberPublicKey);
        }

        $this->messageChats = chatmessage::latest('id')->where('chat_id', $this->chatID)->limit($this->limiterChat)->get()->reverse();
    }

    public function render()
    {
        if(!empty($this->memberPublicKey))
        {
            //load public key to decrypt
            $this->rsa_member->loadKey($this->memberPublicKey);
        }
        
        return view('livewire.direct-chat', 
        ['encrypter' => $this->encrypter, 
        'rsa_self' => $this->rsa_self, 
        'rsa_member' => $this->rsa_member]);
    }
}
