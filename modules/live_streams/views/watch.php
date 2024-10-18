<?php
  $is_webrtc = parse_url($playlist) === false;
?>

<article id="watch-stream">
  <?php if ($is_webrtc): ?>
  <div class="grid" id="webrtc_videos"></div>
  <?php else: ?>
  <video controls src="<?= $playlist ?>"></video>
  <?php endif; ?>
</article>

<?php if ($is_webrtc): ?>
<script async>
const webrtcVideosGrid = document.querySelector('#webrtc_videos');

const connection = new RTCPeerConnection(); 
const mediaStream = new MediaStream();
const remoteDescription = connection.setRemoteDescription(
  new RTCSessionDescription('<?= base64_decode($playlist) ?>')
);

remoteDescription
  .then(() => connection.createAnswer())
  .then((answer) => connection.setLocalDescription(answer))
  .then(() => {
    fetch('/live_streams/webrtc-join/<?= $stream["id"] ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'TrongateToken': '<?= $token ?? $_SESSION["trongatetoken"] ?>',
      },
      body: JSON.stringify({ sdp: connection.localDescription }),
    });
  });

connection.onicecandidate = (event) => {
  if (event.candidate) {
    connection.addIceCandidate(new RTCIceCandidate(event.candidate));
  }
};

connection.ontrack = (event) => {
  stream.addTrack(event.track);

  const video = document.createElement('video');
  video.autoplay = true;
  video.playsinline = true;
  video.srcObject = stream;
  webrtcVideosGrid.appendChild(video);
};
</script>
<?php else: ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const video = document.querySelector('video');
    video.play();
  });
</script>
<?php endif; ?>