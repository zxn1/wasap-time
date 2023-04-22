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

var sdpCaller = null;
var sdpAnswerCaller = null;


let init = async () => {
   localStream = await navigator.mediaDevices.getUserMedia({video:true, audio:true})
   document.getElementById('local-video').srcObject = localStream
}

let createPeerConnection = async () => {
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
            sdpAnswerCaller = JSON.stringify(peerConnection.localDescription)
        }
    }
}


let createAnswer = async () => {
    createPeerConnection()

    let offer = sdpCaller;
    if(!offer) return alert('Retrieve offer from peer first...')

    offer = JSON.parse(offer)
    await peerConnection.setRemoteDescription(offer)
    
    let answer = await peerConnection.createAnswer()
    await peerConnection.setLocalDescription(answer)

    sdpAnswerCaller = JSON.stringify(answer)
}

init();

//trigger get sdp offer from database
document.addEventListener('DOMContentLoaded', function() {
    Livewire.emit('getSDPSender');
    listenSDPSender();
});

//listen for sdp offer
const listenSDPSender = () => {
    Livewire.on('getSDPSender', data => {
        if(data != null)
        {
            sdpCaller = data.sdp_offer;
            Livewire.off('getSDPSender'); //ni memang tak ada function ni. just tuk exit loop
        }
    });
}

//create sdp answer
const detikListenSDP = setInterval(()=>{
    if(sdpCaller != null)
    {
        createAnswer();
        clearInterval(detikListenSDP);
    }
}, 200);

//store the sdp answer to database
const detikSendingSDPAnswer = setInterval(()=>{
    if(sdpAnswerCaller != null)
    {
        emitSDPAnswer();
        clearInterval(detikSendingSDPAnswer);
    }
}, 200);

//emit to update in database
const emitSDPAnswer = () => {
    Livewire.emit('updateAnswer', sdpAnswerCaller);
}