const muxStream = {
    start: async function (id, token) {
        const response = await fetch(`live_streams/mux_start/${id}`, {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "TrongateToken": token
            }
        });

        const data = await response.json();

        if (response.status !== 200) {
            toast(data.message, 'error');
            return false;
        }

        // Show streaming instructions
        if (data.stream_key && data.rtmp_url) {
            const streamingInfo = `
                <div class="streaming-instructions">
                    <h3>Streaming Active!</h3>
                    <p>Your stream is now marked as live. Start broadcasting from your streaming software using:</p>
                    <p><strong>RTMP URL:</strong> ${data.rtmp_url}</p>
                    <p><strong>Stream Key:</strong> ${data.stream_key}</p>
                    <p><strong>Playback URL:</strong> ${data.playback_url}</p>
                </div>
            `;

            // Could display this in a modal or dedicated area
            console.log('Stream started:', data);
        }

        toast(data.message, 'success');
        return true;
    },

    stop: async function (id, token) {
        const response = await fetch(`live_streams/stop/${id}`, {
            method: 'POST',
            headers: {
                "Content-Type": "application/json",
                "TrongateToken": token
            }
        });

        const data = await response.json();

        if (response.status !== 200) {
            toast(data.message, 'error');
            return false;
        }

        toast(data.message, 'success');
        return false; // Stream is now stopped
    }
};

document.addEventListener('DOMContentLoaded', function() {
    const muxActionButton = document.getElementById('mux_action');

    if (!muxActionButton) {
        console.warn('Mux action button not found');
        return;
    }

    const id = muxActionButton.dataset.id;
    const token = muxActionButton.dataset.trongateToken;
    const templates = document.getElementsByTagName('template');
    const goLiveTemplate = templates.namedItem('go_live');
    const goOfflineTemplate = templates.namedItem('go_offline');

    muxActionButton.addEventListener('click', () => {
        const action = muxActionButton.dataset.action;
        muxActionButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

        muxStream[action](id, token).then((live) => {
            let content = '';

            if (live) {
                content = document.importNode(goOfflineTemplate.content.children[0], true)
                muxActionButton.dataset.action = 'stop';
            } else {
                content = document.importNode(goLiveTemplate.content.children[0], true);
                muxActionButton.dataset.action = 'start';
            }

            muxActionButton.innerHTML = content.outerHTML;
        });
    }, false);
});
