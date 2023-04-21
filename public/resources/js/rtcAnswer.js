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


let init = async () => {
   localStream = await navigator.mediaDevices.getUserMedia({video:true, audio:false})
   document.getElementById('user-1').srcObject = localStream
}

let createPeerConnection = async (sdpType, MemberId) => {
    peerConnection = new RTCPeerConnection(servers)

    remoteStream = new MediaStream()
    document.getElementById('user-2').srcObject = remoteStream

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
            document.getElementById(sdpType).value = JSON.stringify(peerConnection.localDescription)
            //client.sendMessageToPeer({text:JSON.stringify({'type':'candidate', 'candidate':event.candidate})}, MemberId)
        }
    }
}

let createOffer = async (MemberId) => {
    
    createPeerConnection('offer-sdp', MemberId)

    let offer = await peerConnection.createOffer()
    await peerConnection.setLocalDescription(offer)

    document.getElementById('offer-sdp').value = JSON.stringify(offer)
    //client.sendMessageToPeer({text:JSON.stringify({'type':'offer', 'offer':offer})}, MemberId)
}

let createAnswer = async (MemberId) => {
    createPeerConnection('answer-sdp', MemberId)

    let offer = document.getElementById('offer-sdp').value
    if(!offer) return alert('Retrieve offer from peer first...')

    offer = JSON.parse(offer)
    await peerConnection.setRemoteDescription(offer)
    
    let answer = await peerConnection.createAnswer()
    await peerConnection.setLocalDescription(answer)

    document.getElementById('answer-sdp').value  = JSON.stringify(answer)
    //client.sendMessageToPeer({text:JSON.stringify({'type':'answer', 'answer':answer})}, MemberId)
}

let addAnswer = async () => {
    let answer = document.getElementById('answer-sdp').value
    if(!answer) return alert('Retrieve answer from peer first...')

    answer = JSON.parse(answer)

    if(!peerConnection.currentRemoteDescription){
        peerConnection.setRemoteDescription(answer)
    }

}

init()

document.getElementById('create-offer').addEventListener('click', createOffer)
document.getElementById('create-answer').addEventListener('click', createAnswer)
document.getElementById('add-answer').addEventListener('click', addAnswer)
