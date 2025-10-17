<article id="watch-stream" data-stream-id="<?= htmlspecialchars($id) ?>">
  <div id="stream-status" class="stream-status <?= $live ? 'live' : 'offline' ?>">
    <?= $live ? '🔴 LIVE' : '⚫ OFFLINE' ?>
  </div>

  <div id="player-container">
    <?php if (!$stream_valid): ?>
      <div id="stream-invalid" class="stream-unavailable">
        <h2>Stream Configuration Error</h2>
        <p>This stream is not properly configured and cannot be viewed.</p>
        <p>Please contact the administrator if you believe this is an error.</p>
      </div>
    <?php elseif (!empty($mux_playback_id) && $live): ?>
      <mux-player
        id="mux-player"
        stream-type="live"
        playback-id="<?= htmlspecialchars($mux_playback_id) ?>"
        metadata-video-title="<?= htmlspecialchars($title) ?>"
        metadata-viewer-user-id="<?= $_SESSION['user_id'] ?? 'anonymous' ?>"
        autoplay
        muted
      ></mux-player>
    <?php else: ?>
      <div id="stream-unavailable" class="stream-unavailable">
        <h2>Stream Not Available</h2>
        <p>This stream is not currently available for viewing.</p>
        <p id="offline-message" <?= $live ? 'style="display:none"' : '' ?>>The stream has not started yet. Check back later!</p>
      </div>
    <?php endif; ?>
  </div>
</article>

<script src="https://cdn.jsdelivr.net/npm/@mux/mux-player"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const streamId = document.getElementById('watch-stream').dataset.streamId;
    const streamStatus = document.getElementById('stream-status');
    const playerContainer = document.getElementById('player-container');
    const offlineMessage = document.getElementById('offline-message');

    // Listen for live_streams channel messages
    window.socket.onMessage('live_streams', function(message) {
        console.log('Stream update received:', message);
        console.log('Current stream ID:', streamId, 'Message stream ID:', message.id);
        console.log('ID comparison:', message.id == streamId, 'Status:', message.status);

        // Check if this message is for our stream
        if (message.id == streamId) {
            console.log('Updating stream status to:', message.status);
            updateStreamStatus(message.status);
        }
    });

    function updateStreamStatus(status) {
        switch(status) {
            case 'live':
                streamStatus.textContent = '🔴 LIVE';
                streamStatus.className = 'stream-status live';

                // Create and inject Mux player for live stream
                <?php if (!empty($mux_playback_id)): ?>
                const livePlayer = `
                    <mux-player
                        id="mux-player"
                        stream-type="live"
                        playback-id="<?= htmlspecialchars($mux_playback_id) ?>"
                        metadata-video-title="<?= htmlspecialchars($title) ?>"
                        metadata-viewer-user-id="<?= $_SESSION['user_id'] ?? 'anonymous' ?>"
                        autoplay
                        muted
                    ></mux-player>
                `;
                playerContainer.innerHTML = livePlayer;
                <?php else: ?>
                console.log('No playback ID available for live player');
                <?php endif; ?>
                break;

            case 'offline':
            case 'disconnected':
                streamStatus.textContent = '⚫ OFFLINE';
                streamStatus.className = 'stream-status offline';

                // Show offline message when stream ends
                const offlineContent = `
                    <div id="stream-unavailable" class="stream-unavailable">
                        <h2>Stream Not Available</h2>
                        <p>This stream is not currently available for viewing.</p>
                        <p id="offline-message">The stream has ended. Check back later!</p>
                    </div>
                `;
                playerContainer.innerHTML = offlineContent;
                break;
        }
    }
});
</script>
