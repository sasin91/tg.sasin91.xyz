const muxStream = {
    start: function (id, token) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', `/live_streams/mux_start/${id}`, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('TrongateToken', token);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    try {
                        const data = JSON.parse(xhr.responseText);

                        if (xhr.status === 200) {
                            toast(data.message, 'success');
                            resolve(true);
                        } else {
                            toast(data.message, 'error');
                            resolve(false);
                        }
                    } catch (e) {
                        toast('Request failed', 'error');
                        resolve(false);
                    }
                }
            };

            xhr.onerror = function() {
                toast('Network error', 'error');
                resolve(false);
            };

            xhr.send();
        });
    },

    stop: function (id, token) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', `/live_streams/stop/${id}`, true);
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.setRequestHeader('TrongateToken', token);

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    try {
                        const data = JSON.parse(xhr.responseText);

                        if (xhr.status === 200) {
                            toast(data.message, 'success');
                            resolve(false); // Stream is now stopped
                        } else {
                            toast(data.message, 'error');
                            resolve(false);
                        }
                    } catch (e) {
                        toast('Request failed', 'error');
                        resolve(false);
                    }
                }
            };

            xhr.onerror = function() {
                toast('Network error', 'error');
                resolve(false);
            };

            xhr.send();
        });
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
