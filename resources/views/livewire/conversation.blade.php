<div>
    <div class="chat">
        <center>
            <h4>Your Direct Message
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-chat-dots" viewBox="0 0 16 16">
                    <path d="M5 8a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm4 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                    <path d="m2.165 15.803.02-.004c1.83-.363 2.948-.842 3.468-1.105A9.06 9.06 0 0 0 8 15c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7c0 1.76.743 3.37 1.97 4.6a10.437 10.437 0 0 1-.524 2.318l-.003.011a10.722 10.722 0 0 1-.244.637c-.079.186.074.394.273.362a21.673 21.673 0 0 0 .693-.125zm.8-3.108a1 1 0 0 0-.287-.801C1.618 10.83 1 9.468 1 8c0-3.192 3.004-6 7-6s7 2.808 7 6c0 3.193-3.004 6-7 6a8.06 8.06 0 0 1-2.088-.272 1 1 0 0 0-.711.074c-.387.196-1.24.57-2.634.893a10.97 10.97 0 0 0 .398-2z"/>
                </svg>
            </h4>
        </center>
        <hr>
        <div class="search-bar">
            <input wire:model='search' type="text" placeholder="Search...">
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search-heart" viewBox="0 0 16 16">
                    <path d="M6.5 4.482c1.664-1.673 5.825 1.254 0 5.018-5.825-3.764-1.664-6.69 0-5.018Z"/>
                    <path d="M13 6.5a6.471 6.471 0 0 1-1.258 3.844c.04.03.078.062.115.098l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1.007 1.007 0 0 1-.1-.115h.002A6.5 6.5 0 1 1 13 6.5ZM6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11Z"/>
                </svg>
            </button>
        </div>
        <div class="chat-list">

        @foreach($directChat as $item)
            @if($item->from_id == session('wasap_sess'))
            <a href="/conversation/{{$item->to_id}}" style="text-decoration: none;">
            @else
            <a href="/conversation/{{$item->from_id}}" style="text-decoration: none;">
            @endif
            <div class="chat-item">
                @if($item->from_id != session('wasap_sess'))
                <img class="chat-avatar" src="https://api.dicebear.com/6.x/personas/svg?seed={{$item->fromName->name}}" alt="Avatar">
                @else
                <img class="chat-avatar" src="https://api.dicebear.com/6.x/personas/svg?seed={{$item->toName->name}}" alt="Avatar">
                @endif
            
                <div class="chat-info">
                @if($item->from_id != session('wasap_sess'))
                <h4 class="chat-name">{{$item->fromName->name}}</h4>
                @else
                <h4 class="chat-name">{{$item->toName->name}}</h4>
                @endif
                <p class="chat-preview" style="color: #707070;">
                @if($item->getLatestMessage[0]->from_id == session('wasap_sess'))
                you : 
                @else
                {{$item->getLatestMessage[0]->randSessions->name}} : 
                @endif
                {{$item->getLatestMessage[0]->chat_message}}
                </p>
                </div>
                <div class="chat-meta">
                <p class="chat-time">{{$item->getLatestMessage[0]->created_at}}</p>
                <span class="chat-unread"><span style="position : relative; top : -7px; left : -1px;">2</span></span>
                </div>
            </div>
            </a>
        @endforeach

        </div>
    </div>
</div>
