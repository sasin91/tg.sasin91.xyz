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
            <dt>RTMP (OBS Studio)</dt>
            <dd><?= $t('Use this if you want to stream to live stream to an audience watching you.') ?></dd>
        </dl>
    </section>
    <section class="card" id="webrtc">
        <h1><?= $t('Start a live conference') ?></h1>
        <dl>
            <dt>WebRTC</dt>
            <dd><?= $t('Starts a live meeting, other people will be able to join.') ?></dd>
        </dl>
        <div class="grid" id="webrtc_videos"></div>
        <template id="go_live">
            <?= $go_live_content ?>
        </template>
        <template id="go_offline">
            <?= $go_offline_content ?>
        </template>

        <button
                id="webrtc_action"
                class="action-button text-fancy"
                data-action="<?= $live ? 'stop' : 'start' ?>"
                data-id="<?= $id ?>"
        >
            <?= $live ? $go_offline_content : $go_live_content ?>
        </button>
    </section>
</article>
<script src="/live_streams_module/js/go_live.js"></script>