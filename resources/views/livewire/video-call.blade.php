{{-- <div>
    <div class="video-call-container" style="margin-top : 5px;">
        @if($endCall != true)
        <div class="video-container" wire:poll='getEndCall'>
            <video id="local-video" autoplay style='border-radius : 10px;' autoplay playsinline></video>
            <video id="remote-video" autoplay style='border-radius : 10px; margin-top : 5px;' autoplay playsinline></video>
        </div>
        @else
        <center>
        call has been ended!
        </center>
        @endif
        <a wire:click='closeCall'>
            <button class="end-call-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-telephone-x-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511zm9.261 1.135a.5.5 0 0 1 .708 0L13 2.793l1.146-1.147a.5.5 0 0 1 .708.708L13.707 3.5l1.147 1.146a.5.5 0 0 1-.708.708L13 4.207l-1.146 1.147a.5.5 0 0 1-.708-.708L12.293 3.5l-1.147-1.146a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>
        </a>
    </div>
</div> --}}

<div>
    <div class="video-call-container" style="margin-top : 5px;">
        @if($endCall != true)
        <div class="video-container" wire:poll='getEndCall'>
            <video id="local-video" autoplay style='border-radius : 10px;' autoplay playsinline draggable="true"></video>
            <video id="remote-video" autoplay style='border-radius : 10px; margin-top : 5px;' autoplay playsinline></video>
        </div>
        @else
        <center>
        call has been ended!
        </center>
        @endif
        <a wire:click='closeCall'>
            <button class="end-call-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-telephone-x-fill" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511zm9.261 1.135a.5.5 0 0 1 .708 0L13 2.793l1.146-1.147a.5.5 0 0 1 .708.708L13.707 3.5l1.147 1.146a.5.5 0 0 1-.708.708L13 4.207l-1.146 1.147a.5.5 0 0 1-.708-.708L12.293 3.5l-1.147-1.146a.5.5 0 0 1 0-.708z"/>
                </svg>
            </button>
        </a>
    </div>
<script>
var elmnt = document.getElementById("local-video");

if ('ontouchstart' in window) {
    elmnt.addEventListener('touchstart', touchStart, false);
    elmnt.addEventListener('touchmove', touchMove, false);
} else {
    elmnt.addEventListener('mousedown', mouseDown, false);
    elmnt.addEventListener('mouseup', mouseUp, false);
}

var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;

function touchStart(e) {
    e.preventDefault();
    pos3 = e.touches[0].clientX;
    pos4 = e.touches[0].clientY;
    elmnt.addEventListener('touchmove', touchMove, false);
}

function touchMove(e) {
    e.preventDefault();
    pos1 = pos3 - e.touches[0].clientX;
    pos2 = pos4 - e.touches[0].clientY;
    pos3 = e.touches[0].clientX;
    pos4 = e.touches[0].clientY;
    elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
    elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
}

function mouseDown(e) {
    e.preventDefault();
    pos3 = e.clientX;
    pos4 = e.clientY;
    document.addEventListener('mousemove', mouseMove, false);
}

function mouseMove(e) {
    e.preventDefault();
    pos1 = pos3 - e.clientX;
    pos2 = pos4 - e.clientY;
    pos3 = e.clientX;
    pos4 = e.clientY;
    elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
    elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
}

function mouseUp() {
    document.removeEventListener('mousemove', mouseMove, false);
    elmnt.removeEventListener('touchmove', touchMove, false);
}

</script>
</div>
