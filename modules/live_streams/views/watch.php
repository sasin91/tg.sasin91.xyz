<article id="watch-stream">
  <?php if (!empty($mux_playback_id) && $live): ?>
    <mux-player
      stream-type="live"
      playback-id="<?= htmlspecialchars($mux_playback_id) ?>"
      metadata-video-title="<?= htmlspecialchars($title) ?>"
      metadata-viewer-user-id="<?= $_SESSION['user_id'] ?? 'anonymous' ?>"
      autoplay
      muted
    ></mux-player>
  <?php elseif (!empty($mux_playback_id)): ?>
    <mux-player
      playback-id="<?= htmlspecialchars($mux_playback_id) ?>"
      metadata-video-title="<?= htmlspecialchars($title) ?>"
      metadata-viewer-user-id="<?= $_SESSION['user_id'] ?? 'anonymous' ?>"
      controls
    ></mux-player>
  <?php else: ?>
    <div class="stream-unavailable">
      <h2>Stream Not Available</h2>
      <p>This stream is not currently available for viewing.</p>
      <?php if (!$live): ?>
        <p>The stream has not started yet. Check back later!</p>
      <?php endif; ?>
    </div>
  <?php endif; ?>
</article>

<script src="https://cdn.jsdelivr.net/npm/@mux/mux-player"></script>