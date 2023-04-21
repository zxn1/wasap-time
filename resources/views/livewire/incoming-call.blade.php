<div wire:poll='getIncomingCall'>
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
          <button class="accept-btn">Accept</button>
          <button class="decline-btn">Decline</button>
        </div>
    </div>
    @endif
</div>
