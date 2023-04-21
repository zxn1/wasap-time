<div class="container">
@if($session != null)
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
	<div class="chat" wire:poll="getMessage">

        <div wire:click='addLimiter' class="view-more-messages">
            <button class="btn-view-more">View More Messages</button>
        </div>     

        @foreach($chats as $chat)

        @if($chat->from_id == '111111111111111')
        <div class="message system">
			<div class="text">
				<p>{{ $chat->messages }} has joined the chat.</p>
			</div>
		</div>
        @continue
        @endif

        @if($chat->from_id != $session)
		<div class="message received">
			<div class="avatar"></div>
			<div class="text">
                <span style="font-size : 11px;">{!! '@' !!}{{ $chat->randSessions->name }}</span>
				<p>{{ $chat->messages }}</p>
				<div class="time">{{ $chat->created_at }}</div>
			</div>
		</div>
        @continue
        @endif
		
        @if($chat->from_id == $session)
		<div class="message sent">
        <div class="avatar"></div>
			<div class="text">
                <span style="font-size : 11px; color : rgb(175, 175, 175);">You</span>
				<p style="position : relative; top : -5px;">{{ $chat->messages }}</p>
				<div class="time">{{ $chat->created_at }}</div>
			</div>
		</div>
        @continue
        @endif
	
        @endforeach
	</div>

    <div class="chat-input-holder">
        <div class="chat-input">
            <input wire:model.defer="chatInput" type="text" class="chat-input-field" placeholder="Type a message">
            <button wire:click='sendMessage' class="chat-send-button">
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
@endif
</div>