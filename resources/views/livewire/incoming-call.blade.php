@if(session('wasap_sess'))
<div wire:poll="getIncomingCall" interval="2s">
    <head>
        <link rel="stylesheet" href="/resources/css/incomingCall.css">
    </head>
    @if($showCall == 2)
    <div class="incoming-call">
        <div class="caller-info">
          <img src="caller-avatar.png" alt="Caller Avatar">
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
