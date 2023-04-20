<div>
    @if($chat == false)
        @if($ses != '')
        <div class="modal-content" style="margin-top : 100px;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Set Your Name</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="exampleInputEmail1">Your session string</label>
                    <input type="session" class="form-control" id="sessionInput" aria-describedby="session" value="{{$ses}}" placeholder="session">
                    <small id="emailHelp" class="form-text text-muted">never share your session with anyone else.</small>
                </div>
                <div class="form-group">
                    <label for="name">Your Name</label>
                    <input type="text" wire:model.defer="name" class="form-control" id="nameInput" placeholder="Name">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" wire:click="setSession">Submit</button>
            </div>
        </div>
        @else
        <div class="jumbotron">
        <h1 class="display-4">Welcome, back!</h1>
        <p class="lead">Your previous session is...</p>
        <hr class="my-4">
        <p style="font-size : 8px;">{{ session('wasap_sess') }}</p>
        <p class="lead">
            <button class="btn btn-primary btn-lg" role="button">Continue Chat</button>
            <button class="btn btn-danger btn-lg" role="button" wire:click="removeSession">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5Zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6Z"/>
                    <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1ZM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118ZM2.5 3h11V2h-11v1Z"/>
                </svg>
            </button>
        </p>
        </div>
        @endif
    @endif
</div>
