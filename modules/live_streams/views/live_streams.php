<template id="live_stream-live-tooltip">
    <div class="tooltip status-container-wrapper">
        <span class="status-container">
          <svg class="status" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <circle cx="12" cy="12" r="10" fill="#ff595e" />
            </svg>
        </span>
        <span class="tooltip-text">Live</span>
    </div>
</template>

<template id="live_stream-details">
    <dl class="details">
        <dt class="sr-only"><?= $t('Summary & Description') ?></dt>
        <dd>
            <details>
                <summary class="title">[summary]</summary>
                <p class="description">[description]</p>
            </details>
        </dd>
    </dl>
</template>

<template id="live_stream-action-viewers">
    <div class="action">
        <span class="action-viewers">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g stroke="var(--primary)" stroke-width="1.5" stroke-miterlimit="10">
                <path d="M12 12.45c1.65 0 3-1.35 3-3s-1.35-3-3-3-3 1.35-3 3 1.35 3 3 3zM17.85 11.7c1.32 0 2.4-1.08 2.4-2.4 0-1.32-1.08-2.4-2.4-2.4-1.32 0-2.4 1.08-2.4 2.4 0 1.32 1.08 2.4 2.4 2.4zM6.15 11.7c-1.32 0-2.4-1.08-2.4-2.4 0-1.32 1.08-2.4 2.4-2.4 1.32 0 2.4 1.08 2.4 2.4 0 1.32-1.08 2.4-2.4 2.4z" />
                <path d="M10.8 14.1c-2.04 0-3.6 1.656-3.6 3.6 0 .72.15 1.44.45 2.1h6.3c.3-.66.45-1.38.45-2.1 0-1.944-1.56-3.6-3.6-3.6zM6.15 13.95c-1.92 0-3.45 1.53-3.45 3.45 0 .75.21 1.5.54 2.1h3.99A5.88 5.88 0 0 1 6.6 19.5c-1.62 0-3.12-1.02-3.12-2.85-.03-2.31 1.68-3.9 3.84-3.9h.18zM17.85 13.95c1.92 0 3.45 1.53 3.45 3.45 0 .75-.21 1.5-.54 2.1h-3.99a2.877 2.877 0 0 1-.54-2.1c0-1.62 1.5-2.7 3.12-2.7z" />
              </g>
            </svg>
            <span class="count">0</span>
            <?= $t('Viewers') ?>
        </span>
    </div>
</template>

<template id="live_stream-action-watch">
    <a class="watch-stream" data-playlist="">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="var(--primary)">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.277A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.895L15 14" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 8v8m-8.25 4H7a2 2 0 01-2-2v-8a2 2 0 012-2h3.75M16 4h-4a2 2 0 00-2 2v.25M16 4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2.343m5-2.593V7.25" />
        </svg>
        <?= $t('Watch') ?>
    </a>
</template>

<template id="live_stream-action-webrtc_join">
    <a class="join-session" data-id="">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="var(--primary)">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.277A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.895L15 14" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 8v8m-8.25 4H7a2 2 0 01-2-2v-8a2 2 0 012-2h3.75M16 4h-4a2 2 0 00-2 2v.25M16 4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2.343m5-2.593V7.25" />
        </svg>
        <?= $t('Join') ?>
    </a>
</template>

<template id="live_stream-action-start">
    <a href="/live_streams/start/:id">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="100" height="100" fill="var(--primary)">
            <path d="M256 144c-66.274 0-120 53.726-120 120s53.726 120 120 120 120-53.726 120-120-53.726-120-120-120zm0 192c-39.701 0-72-32.299-72-72s32.299-72 72-72 72 32.299 72 72-32.299 72-72 72zm240-232h-88c-22.091 0-40-17.909-40-40 0-13.255-10.745-24-24-24h-128c-13.255 0-24 10.745-24 24 0 22.091-17.909 40-40 40h-88c-26.51 0-48 21.49-48 48v256c0 26.51 21.49 48 48 48h448c26.51 0 48-21.49 48-48v-256c0-26.51-21.49-48-48-48zm8 304c0 4.418-3.582 8-8 8h-448c-4.418 0-8-3.582-8-8v-256c0-4.418 3.582-8 8-8h92.118c29.794 0 50.188-20.497 50.188-48 0-4.418 3.582-8 8-8h128c4.418 0 8 3.582 8 8 0 27.503 20.394 48 50.188 48h92.118c4.418 0 8 3.582 8 8v256z"/>
        </svg>
        &nbsp;
        <?= $t('Start') ?>
    </a>
</template>

<template id="live_stream-starts_at">
    <time></time>
</template>

<article>
    <h1 class="heading"><?= $t('Live streams') ?></h1>

    <a href="<?= BASE_URL ?>live_streams/new">
      <?= $t('Create new livestream') ?>
    </a>
  
    <section id="live">
        <ul role="list" class="grid" id="live_streams">
        </ul>
    </section>
</article>

<script>
    document.addEventListener('websocket:init.done', function () {
        var live_streams_script = document.createElement('script');
        live_streams_script.src = '/live_streams_module/js/live_streams.js';
        live_streams_script.type = 'text/javascript';

        live_streams_script.addEventListener('load', function () {
            renderLiveStreams(
                JSON.parse('<?= json_encode(
                    $streams,
                    JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT | JSON_HEX_APOS | JSON_HEX_AMP
                ) ?>')
            );
        })
        document.head.appendChild(live_streams_script);
    });

    // document.addEventListener('DOMContentLoaded', function() {
    //     let peerConnection = new RTCPeerConnection();
    //
    //     // Send offers or answers through the WebSocket
    //     async function createOffer() {
    //         const offer = await peerConnection.createOffer();
    //         await peerConnection.setLocalDescription(offer);
    //         socket.send(JSON.stringify({ type: 'offer', offer: offer }));
    //     }
    //
    //     socket.onmessage = async (event) => {
    //         const message = JSON.parse(event.data);
    //
    //         if (message.type === 'offer') {
    //             await peerConnection.setRemoteDescription(new RTCSessionDescription(message.offer));
    //             const answer = await peerConnection.createAnswer();
    //             await peerConnection.setLocalDescription(answer);
    //             socket.send(JSON.stringify({ type: 'answer', answer: answer }));
    //         }
    //
    //         if (message.type === 'answer') {
    //             await peerConnection.setRemoteDescription(new RTCSessionDescription(message.answer));
    //         }
    //
    //         if (message.type === 'candidate') {
    //             await peerConnection.addIceCandidate(new RTCIceCandidate(message.candidate));
    //         }
    //     };
    //
    //     peerConnection.onicecandidate = (event) => {
    //         if (event.candidate) {
    //             socket.send(JSON.stringify({ type: 'candidate', candidate: event.candidate }));
    //         }
    //     };
    //
    //     document.querySelectorAll('.join-session').forEach((element) => {
    //        element.addEventListener('click', createOffer);
    //     });
    //
    //     document.querySelectorAll('.watch-stream').forEach((element) => {
    //         element.addEventListener('click', watchLiveStream(element.dataset.playlist));
    //     });
    // });
</script>
