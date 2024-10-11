const webrtc = {
    connection: null,
    stream: null,

    grid: document.getElementById('webrtc_videos'),
    appendVideoToGrid: function (stream) {
        const video = document.createElement('video');
        video.autoplay = true;
        video.playsinline = true;
        video.srcObject = stream;
        webrtc.grid.appendChild(video);
    },
    /**
     * Handles incoming WebRTC remote messages.
     *
     * Processes the given message by either setting the remote session description
     * if 'sdp' is present or adding ICE candidates if 'ice' is present.
     *
     * @param {Object} message - The remote message containing either SDP or ICE data.
     * @param {Object} message.sdp - The Session Description Protocol (SDP) data for setting the remote description.
     * @param {Object} message.ice - The Interactive Connectivity Establishment (ICE) candidate for adding to the connection.
     * @returns {Promise<void>} A promise that resolves when the message has been processed.
     */
    handleRemoteMessage: function(message) {
        if (message.sdp) {
            return webrtc.connection.setRemoteDescription(new RTCSessionDescription(message.sdp));
        } else if (message.ice) {
            return webrtc.connection.addIceCandidate(new RTCIceCandidate(message.ice));
        }
    },
    goLive: async function (id) {
        const response = await fetch(`live_streams/webrtc/${id}`, {
            method: 'POST'
        });

        const { message } = await response.json();

        if (response.status === 200) {
            webrtc.stream = await navigator.mediaDevices.getUserMedia({ video:true, audio:true });
            webrtc.appendVideoToGrid(webrtc.stream);
            webrtc.connection = new RTCPeerConnection();

            webrtc.stream.getTracks().forEach(track => webrtc.connection.addTrack(track, webrtc.stream));

            webrtc.connection.onicecandidate = (ev) => {
                if (ev.candidate) {
                    socket.send({ ice: ev.candidate });
                }
            };

            webrtc.connection.ontrack = (ev) => {
                webrtc.appendVideoToGrid(ev.streams[0]);
            };

            toast(message, 'success text-fancy');
        } else {
            toast(message, 'error');
        }
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const webrtcGoLiveButton = document.getElementById('webrtc_go-live');
    const id = webrtcGoLiveButton.dataset.id;

    webrtcGoLiveButton.addEventListener('click', () => {
        return webrtc.goLive(id);
    }, false);
});