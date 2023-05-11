@if(session('wasap_sess'))
<div wire:poll.6000ms="getIncomingCall">
    <head>
        <link rel="stylesheet" href="/resources/css/incomingCall.css">
    </head>
    @if($showCall == 2)
    <div class="incoming-call">
        <div class="caller-info">
          {{-- <img src="caller-avatar.png" alt="Caller Avatar"> --}}
          <img class="chat-avatar" src="https://api.dicebear.com/6.x/personas/svg?seed={{$nameCaller}}" alt="Caller Avatar">
          <h3 style="width : 50px;">{{ $nameCaller }}</h3>
          <p>Incoming call...</p>
        </div>
        <div class="call-controls">
        <a href="/accept-call">
          <button class="accept-btn">Accept</button>
        </a>
          <button wire:click='declineCall' class="decline-btn">Decline</button>
        </div>
    </div>
    <audio autoplay>
        <source src="/resources/ringtones/ayuhtinggalkandia-ring1.mp3" type="audio/mpeg">
    </audio>
    @endif
</div>
@endif