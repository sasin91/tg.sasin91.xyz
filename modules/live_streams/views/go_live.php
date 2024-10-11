<article class="grid" id="go_live">
    <section class="card" id="rtmp-details">
        <h1>Start a live stream</h1>

        <dl>
            <dt>RTMP (OBS Studio)</dt>
            <dd>Use this if you want to stream to live stream to an audience watching you.</dd>
        </dl>
    </section>

    <section class="card" id="webrtc">
        <h1>Start a live conference</h1>

        <dl>
            <dt>WebRTC</dt>
            <dd>Starts a live meeting, other people will be able to join.</dd>
        </dl>

        <div class="grid" id="webrtc_videos"></div>

        <button id="webrtc_go-live" class="action-button text-fancy" data-id="<?= $id ?>">Go live</button>
    </section>
</article>

<script src="/live_streams_module/js/go_live.js"></script>