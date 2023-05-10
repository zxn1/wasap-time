<div class="container">

<head>
	<title>Wasap</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			font-size: 16px;
			color: #333;
			background-color: #f2f2f2;
		}
	</style>
    <link rel="stylesheet" href="/resources/css/chat.css">
</head>
<body>
	<div class="chat" wire:poll='getMessage'>
    <div wire:click='viewMoreChat' class="view-more-messages">
        <button class="btn-view-more">View More Messages</button>
    </div>

    @forelse($messageChats as $chat)     

        @if($chat->from_id == $session)

        <?php
        //check authority and integrity
        $receivedHmac = $chat->checkhmac;
        
        $calculatedHmac = hash_hmac('sha256', $chat->chat_message, $encrypter->getKey());

        if($calculatedHmac === $receivedHmac)
        {
        ?>

		<div class="message received">
			<div class="avatar" style="overflow : hidden;">
                <img src="https://api.dicebear.com/6.x/personas/svg?seed={{$chat->randSessions->name}}"/>
            </div>
			<div class="text">
                <span style="font-size : 11px;">{!! '@' !!}{{ $chat->randSessions->name }}</span>
				<p><?php echo $rsa_member->decrypt($chat->chat_message); ?></p>
				<div class="time">{{ $chat->created_at }}</div>
			</div>
		</div>

        <?php
        } else {
        ?>
        <div class="message received">
			<div class="avatar" style="overflow : hidden;">
                <svg fill="currentColor" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                height="100%" viewBox="0 0 33.834 33.834"
                xml:space="preserve">
            <g>
                <path d="M32.253,29.334v4.5H1.581v-4.501c0-2.125,1.832-4.741,4.07-5.804l4.98-2.366l3.457,7.204l1.77-4.799
                    c0.349,0.066,0.695,0.154,1.059,0.154s0.709-0.088,1.059-0.154l1.68,4.563l3.389-7.048l5.141,2.445
                    C30.421,24.591,32.253,27.207,32.253,29.334z M6.105,13.562v-3.25c0-0.551,0.287-1.034,0.72-1.312c0.581-5.058,4.883-9,10.094-9
                    s9.514,3.942,10.096,9c0.432,0.278,0.719,0.761,0.719,1.312v3.25c0,0.863-0.699,1.563-1.563,1.563s-1.563-0.7-1.563-1.563v-0.683
                    c-0.846,4.255-3.961,8.205-7.688,8.205c-3.727,0-6.842-3.95-7.688-8.205v0.683c0,0.7-0.465,1.286-1.1,1.485
                    c0.622,2.117,2.002,3.946,3.908,5.146c0.352-0.116,0.796-0.094,1.227,0.13c0.692,0.36,1.045,1.06,0.783,1.56
                    c-0.261,0.5-1.033,0.612-1.729,0.251c-0.508-0.265-0.83-0.71-0.864-1.126c-2.183-1.396-3.731-3.533-4.37-5.998
                    C6.513,14.78,6.105,14.22,6.105,13.562z M7.89,8.635c0.047,0.003,0.092,0.004,0.137,0.021C8.14,8.698,8.222,8.779,8.279,8.874
                    c0.339,0.144,0.609,0.407,0.775,0.733C9.515,5.286,12.855,3,16.917,3c4.062,0,7.402,2.286,7.863,6.607
                    c0.229-0.449,0.664-0.77,1.185-0.837c-0.676-4.393-4.47-7.771-9.048-7.771C12.386,1,8.622,4.309,7.89,8.635z"/>
            </g>
            </svg>
            </div>
			<div class="text">
                <span style="font-size : 11px;">{!! '@' !!}{{ $chat->randSessions->name }}</span>
				<p style="font-size : 12px; color : #666666;">messages cannot be read due to questionable integrity and authorship</p>
				<div class="time">{{ $chat->created_at }}</div>
			</div>
		</div>
        <?php
        }
        ?>

        @else
		<div class="message sent">
            <div class="avatar" style="overflow : hidden;">
                <img src="https://api.dicebear.com/6.x/personas/svg?seed={{$chat->randSessions->name}}"/>
            </div>
			<div class="text">
                <span style="font-size : 11px; color : rgb(175, 175, 175);">You</span>
				<p style="position : relative; top : -5px;"><?php echo $rsa_self->decrypt($chat->chat_message); ?></p>
				<div class="time">{{ $chat->created_at }}</div>
			</div>
		</div>
        @endif
    
    @empty
    <div class="mt-5 m-5 text-center text-xl">
        <span>There is no conversation yet, let's start! <br></br>
            <svg class="svg-icon" height="30%" viewBox="0 0 20 20">
                <path fill="currentColor" d="M16.853,8.355V5.888c0-3.015-2.467-5.482-5.482-5.482H8.629c-3.015,0-5.482,2.467-5.482,5.482v2.467l-2.741,7.127c0,1.371,4.295,4.112,9.594,4.112s9.594-2.741,9.594-4.112L16.853,8.355z M5.888,17.367c-0.284,0-0.514-0.23-0.514-0.514c0-0.284,0.23-0.514,0.514-0.514c0.284,0,0.514,0.23,0.514,0.514C6.402,17.137,6.173,17.367,5.888,17.367z M5.203,10c0-0.377,0.19-0.928,0.423-1.225c0,0,0.651-0.831,1.976-0.831c0.672,0,1.141,0.309,1.141,0.309C9.057,8.46,9.315,8.938,9.315,9.315v1.028c0,0.188-0.308,0.343-0.685,0.343H5.888C5.511,10.685,5.203,10.377,5.203,10z M7.944,16.853H7.259v-1.371l0.685-0.685V16.853z M9.657,16.853H8.629v-2.741h1.028V16.853zM8.972,13.426v-1.028c0-0.568,0.46-1.028,1.028-1.028c0.568,0,1.028,0.46,1.028,1.028v1.028H8.972z M11.371,16.853h-1.028v-2.741h1.028V16.853z M12.741,16.853h-0.685v-2.056l0.685,0.685V16.853z M14.112,17.367c-0.284,0-0.514-0.23-0.514-0.514c0-0.284,0.23-0.514,0.514-0.514c0.284,0,0.514,0.23,0.514,0.514C14.626,17.137,14.396,17.367,14.112,17.367z M14.112,10.685h-2.741c-0.377,0-0.685-0.154-0.685-0.343V9.315c0-0.377,0.258-0.855,0.572-1.062c0,0,0.469-0.309,1.141-0.309c1.325,0,1.976,0.831,1.976,0.831c0.232,0.297,0.423,0.848,0.423,1.225S14.489,10.685,14.112,10.685z M18.347,15.801c-0.041,0.016-0.083,0.023-0.124,0.023c-0.137,0-0.267-0.083-0.319-0.218l-2.492-6.401c-0.659-1.647-1.474-2.289-2.905-2.289c-0.95,0-1.746,0.589-1.754,0.595c-0.422,0.317-1.084,0.316-1.507,0C9.239,7.505,8.435,6.916,7.492,6.916c-1.431,0-2.246,0.642-2.906,2.292l-2.491,6.398c-0.069,0.176-0.268,0.264-0.443,0.195c-0.176-0.068-0.264-0.267-0.195-0.444l2.492-6.401c0.765-1.911,1.824-2.726,3.543-2.726c1.176,0,2.125,0.702,2.165,0.731c0.179,0.135,0.506,0.135,0.685,0c0.04-0.029,0.99-0.731,2.165-0.731c1.719,0,2.779,0.814,3.542,2.723l2.493,6.404C18.611,15.534,18.524,15.733,18.347,15.801z"></path>
            </svg>
        </span>
    </div>
    @endforelse
	</div>

    <div class="chat-input-holder">
        <div class="chat-input">
            <input wire:model.defer="messageInput" type="text" class="chat-input-field" placeholder="Type a message">
            <button wire:click='sentMessage' class="chat-send-button">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16">
                    <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z"/>
                </svg>
            </button>
        </div>
    </div>
</body>
<script>
    var chatContainer = document.querySelector(".chat");

    // Function to scroll to the bottom of the container
    function scrollToBottom() {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }

    // Call the scrollToBottom function every second
    const detik = setInterval(() => {
        scrollToBottom();
        clearInterval(detik);
    }, 1000);
</script>

</div>