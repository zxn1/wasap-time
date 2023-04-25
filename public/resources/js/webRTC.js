let peerConnection;
let localStream;
let remoteStream;

const servers = {
  iceServers: [
    {
      urls: 'stun:198.98.57.19:3478',
    },
    {
      urls: 'turn:198.98.57.19:3478?transport=udp',
      username: 'tester',
      credential: 'tester123'
    }
  ]
};

var sdpOffer = null;
var sdpAnswer = null;


// let init = async () => {
//    localStream = await navigator.mediaDevices.getUserMedia({video:true, audio:true})
//    document.getElementById('local-video').srcObject = localStream
// }

let init = async () => {
    try {
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        document.getElementById('local-video').srcObject = localStream;
    } catch (error) {
        alert('Error accessing media devices.');
    }
};

let createPeerConnection = async (sdpType) => {
    peerConnection = new RTCPeerConnection(servers)

    remoteStream = new MediaStream()
    document.getElementById('remote-video').srcObject = remoteStream

    localStream.getTracks().forEach((track) => {
        peerConnection.addTrack(track, localStream)
    })

    peerConnection.ontrack = async (event) => {
        event.streams[0].getTracks().forEach((track) => {
            remoteStream.addTrack(track)
        })
    }

    peerConnection.onicecandidate = async (event) => {
        if(event.candidate){
            sdpType = JSON.stringify(peerConnection.localDescription)
        }
    }
}

let createOffer = async () => {
    
    createPeerConnection(sdpOffer)

    let offer = await peerConnection.createOffer()
    await peerConnection.setLocalDescription(offer)

    sdpOffer = JSON.stringify(offer)
}

let addAnswer = async () => {
    let answer = dataAnswer
    if(!answer) return alert('Retrieve answer from peer first...')

    answer = JSON.parse(answer)

    if(!peerConnection.currentRemoteDescription){
        peerConnection.setRemoteDescription(answer)
    }
}

init();

//create sdp offer
document.addEventListener('DOMContentLoaded', function() {
    Livewire.emit('getSDP');
    const detik = setInterval(()=>{
        if(document.getElementById('local-video').srcObject !== null)
        {
            createOffer();
            clearInterval(detik);
        }
    }, 1000);
});

//emit sdp offer and store to database
const detikSDP = setInterval(()=>{
    if(sdpOffer != null)
    {
        emitSDP();
        clearInterval(detikSDP);
    }
}, 100);

const emitSDP = () => {
    Livewire.emit('emitSDP', sdpOffer);
}

//emit to listen SDP Answer
var dataAnswer = null;
        
Livewire.on('getSDP', data => {
    dataAnswer = data;
    if(dataAnswer != null)
    {
        Livewire.off('getSDP'); //make an error. to stop async function
    }
});

const detikSDPAnswer = setInterval(()=>{
    if(dataAnswer!= null)
    {
        addAnswer();
        clearInterval(dataAnswer);
    }
}, 500);