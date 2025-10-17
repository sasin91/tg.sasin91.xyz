<?php
ob_start();
require __DIR__ . '/partials/go_live.php';
$go_live_content = ob_get_clean();

ob_start();
require __DIR__ . '/partials/go_offline.php';
$go_offline_content = ob_get_clean();
?>

<article class="grid" id="go_live">
    <section class="card" id="rtmp-details">
        <h1><?= $t('Start a live stream') ?></h1>
        <dl>
            <dt>RTMP (OBS Studio / Streaming Software)</dt>
            <dd><?= $t('Use your favorite streaming software like OBS Studio to stream to the provided RTMP endpoint.') ?></dd>
        </dl>

        <?php if (!empty($mux_stream_key)): ?>
        <div class="rtmp-info">
            <h3><?= $t('Streaming Details') ?></h3>
            <div class="stream-detail">
                <label><?= $t('RTMP URL:') ?></label>
                <input type="text" readonly value="rtmp://global-live.mux.com:5222/live" id="rtmp-url">
                <button onclick="navigator.clipboard.writeText(document.getElementById('rtmp-url').value)"><?= $t('Copy') ?></button>
            </div>
            <div class="stream-detail">
                <label><?= $t('Stream Key:') ?></label>
                <input type="text" readonly value="<?= htmlspecialchars($mux_stream_key) ?>" id="stream-key">
                <button onclick="navigator.clipboard.writeText(document.getElementById('stream-key').value)"><?= $t('Copy') ?></button>
            </div>
            <?php if (!empty($mux_playback_id)): ?>
            <div class="stream-detail">
                <label><?= $t('Playback URL:') ?></label>
                <input type="text" readonly value="https://stream.mux.com/<?= htmlspecialchars($mux_playback_id) ?>.m3u8" id="playback-url">
                <button onclick="navigator.clipboard.writeText(document.getElementById('playback-url').value)"><?= $t('Copy') ?></button>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </section>
    <section class="card" id="stream-control">
        <h1><?= $t('Stream Control') ?></h1>
        <p><?= $t('Mark your stream as live once you start broadcasting from your streaming software.') ?></p>

        <template id="go_live">
            <?= $go_live_content ?>
        </template>
        <template id="go_offline">
            <?= $go_offline_content ?>
        </template>

        <button
                id="mux_action"
                class="action-button text-fancy"
                data-action="<?= $live ? 'stop' : 'start' ?>"
                data-id="<?= $id ?>"
                data-trongate-token="<?= $token ?>"
        >
            <?= $live ? $go_offline_content : $go_live_content ?>
        </button>
    </section>
</article>
<script src="/live_streams_module/js/go_live.js"></script>