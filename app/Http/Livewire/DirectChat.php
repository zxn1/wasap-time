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
            $memberPublicKey = null,
            $botActivate = false;
    
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

    public function botInProgress()
    {
        $this->botActivate = !$this->botActivate;
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

        //chat opened
        $this->seenMessage();
        
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

        if($this->botActivate == false)
        { 
            $this->storeMessageInDB($privateKey);
        } else {
            //load public key to decrypt
            $this->rsa_member->loadKey($this->memberPublicKey);

            //check if count more than 20 chat
            $countChat = count($this->messageChats);
            if($countChat > 20)
            {
                //do something
                $countChat = 5;
                return 0;
            }

            //get your name and friend name
            $self_name = null; $friend_name = null;
            
            //change fetched model to prompt
            $prompt = '';
            for($i = 0; $i < $countChat; $i++)
            {
                if($this->messageChats[$i]->from_id == $this->session)
                {
                    $friend_name = $this->messageChats[$i]->randSessions->name;
                    $message = $this->rsa_member->decrypt($this->messageChats[$i]->chat_message);

                    if (strpos($message, '###@$AI_SIFUU_300#@$###:') !== false)
                    {
                        $message = str_replace('###@$AI_SIFUU_300#@$###:', '', $message);
                        $prompt = $prompt . '\nSifuu: ' . $message;
                    } else {
                        $prompt = $prompt . '\n' . $friend_name . ': ' . $message;
                    }
                } else {
                    $self_name = $this->messageChats[$i]->randSessions->name;
                    $prompt = $prompt . '\n' . $this->messageChats[$i]->randSessions->name . ': ' . $this->rsa_self->decrypt($this->messageChats[$i]->chat_message);
                }
            }
            $prompt = $prompt . '\n' . $self_name . ': ' . $this->messageInput;

            //insert the question in database first
            $this->storeMessageInDB($privateKey);

            //make request to text-davinci-300
            $response = $this->askSifuu($prompt, $friend_name, $self_name);

            //purify the output
            //ensure no Sifuu string to be stored in database
            $response = htmlspecialchars($response);
            if(strpos($response, 'Sifuu:') !== false)
            {
                $response = str_replace('Sifuu:', '', $response);
            }

            //ensure no ###@$AI_SIFUU_300#@$###: to be stored in database
            if(strpos($response, '###@$AI_SIFUU_300#@$###:') !== false)
            {
                $response = str_replace('###@$AI_SIFUU_300#@$###:', '', $response);
            }

            //just add in once only
            $response = '###@$AI_SIFUU_300#@$###:' . $response;
            
            //store the output in database as AI
            //do encryption
            $this->rsa->loadKey($privateKey);
            $encrypted_message = $this->rsa->encrypt($response);

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
        }

        $this->messageInput = '';
    }

    protected function storeMessageInDB($privateKey)
    {
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
    }

    protected function askSifuu($prompt, $friend_name, $self_name)
    {
        //get key first from env
        $apiKey = '';

        // // Initialize a cURL session
        $endpoint = 'https://api.openai.com/v1/completions';

        // Set the request body
        $body = [
            'model' => 'text-davinci-003',
            'prompt' => 'The following is a conversation with an AI assistant. The assistant is helpful, creative, clever, and very friendly. The AI name is Sifuu.\n'.
                        $prompt,
            'max_tokens' => 150,
            'temperature' => 0.9,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0.6,
            'stop'=> [' ' . $friend_name . ':', ' ' . $self_name . ':', ' Sifuu:']
        ];

        // Set the request headers
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey,
        ];

        // Set the request options
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", $headers),
                'content' => json_encode($body),
            ],
        ];

        // Make the request and capture the response
        try {
            $response = file_get_contents($endpoint, false, stream_context_create($options));
        } catch (Exception $ex)
        {
            //return inertia('Response', ['response' => 'Failed to retrieve the information!']);
        }

        // Extract the generated text from the response
        $res = json_decode($response, true);
        $text = $res['choices'][0]['text'];

        return $text;
    }

    //this function for checking for update - polling
    public function getMessage()
    {
        //get member public key
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

        //chat opened
        $this->seenMessage();
    }

    protected function seenMessage()
    {
        //chat opened - update received to seen status
        $id_received_arr = [];

        for($i = 0; $i < count($this->messageChats); $i++)
        {
            if(($this->messageChats[$i]->have_read == 'received') && ($this->messageChats[$i]->from_id == $this->session))
            {
                array_push($id_received_arr, $this->messageChats[$i]->id);
            }
        }

        //update received to seen
        chatmessage::whereIn('id', $id_received_arr)->update(['have_read' => 'seen']);
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
