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
    start: async function (id) {
        const response = await fetch(`live_streams/webrtc_start/${id}`, {
            method: 'POST'
        });

        const { message } = await response.json();

        if (response.status !== 200) {
            toast(message, 'error');

            return false;
        }

        webrtc.stream = await navigator.mediaDevices.getUserMedia({ video:true, audio:true });
        webrtc.appendVideoToGrid(webrtc.stream);
        webrtc.connection = new RTCPeerConnection();

        webrtc.stream.getTracks().forEach(track => webrtc.connection.addTrack(track, webrtc.stream));

        webrtc.connection.onicecandidate = (ev) => {
            if (ev.candidate) {
                socket.send(JSON.stringify({ ice: ev.candidate }));
            }
        };

        webrtc.connection.ontrack = (ev) => {
            webrtc.appendVideoToGrid(ev.streams[0]);
        };

        const offer = await webrtc.connection.createOffer();
        webrtc.connection.setLocalDescription(offer);
        socket.send(JSON.stringify({
            type: 'webrtc',
            intent: 'start',
            payload: {
                sdp: offer
            }
        }));

        toast(message, 'success');

        return true;
    },

    stop: async function (id) {
        const response = await fetch(`live_streams/webrtc_stop/${id}`, {
            method: 'POST'
        });

        const { message } = await response.json();

        if (response.status !== 200) {
            toast(message, 'error');

            return false;
        }

        if (webrtc.connection) {
            webrtc.connection.close();
            webrtc.connection = null;
        }

        if (webrtc.stream) {
            webrtc.stream.getTracks().forEach(track => track.stop());
            webrtc.stream = null;
        }

        webrtc.grid.innerHTML = '';
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const webrtcActionButton = document.getElementById('webrtc_action');
    const id = webrtcActionButton.dataset.id;
    const templates = document.getElementsByTagName('template');
    const goLiveTemplate = templates.namedItem('go_live');
    const goOfflineTemplate = templates.namedItem('go_offline');

    webrtcActionButton.addEventListener('click', () => {
        const action = webrtcActionButton.dataset.action;
        webrtcActionButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

        webrtc[action](id).then((live) => {
            let content = '';

            if (live) {
                content = document.importNode(goOfflineTemplate.content.children[0], true)
                webrtcActionButton.dataset.action = 'stop';
            } else {
                content = document.importNode(goLiveTemplate.content.children[0], true);
                webrtcActionButton.dataset.action = 'start';
            }

            webrtcActionButton.innerHTML = content.outerHTML;
        });
    }, false);
});