<div data-player="<?= out(json_encode($player)); ?>" data-server="my_server" class="game-container" id="container">
    <div class="game-ui" id="game-ui">
        <div class="stats-panel">
            <div class="stats-container" id="left-ui-area">
                <div class="stats-row">
                    <div class="stat-values">
                        <div class="stat-value" id="health">
                            <?= number_format($player->health, 0) ?>
                        </div>
                        <div class="stat-value" id="mana">
                            <?= number_format($player->mana, 0) ?>
                        </div>
                    </div>
                    <div class="stat-icons">
                        <div class="health-icon"></div>
                        <div class="mana-icon"></div>
                    </div>
                    <div class="stat-bars">
                        <div class="stat-bar">
                            <div class="health-bar"></div>
                        </div>
                        <div class="stat-bar">
                            <div class="mana-bar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="equipment-panel">
            <div class="equipment-container" id="right-ui-area">
                <div class="stats-row">
                    <div class="weapon-stats">
                        <div class="stat-value"></div>
                        <div class="weapon-pic-text">25</div>
                    </div>
                    <div class="icon-container">
                        <div class="currency-icon"></div>
                        <div class="safety-icon"></div>
                    </div>
                    <div class="player-info">
                        <div class="player-name">👋 <?= $player->name ?></div>
                        <div class="latency"><?= number_format($latency, 0) ?>ms</div>
                        <div class="latency-tooltip">Ping</div>
                    </div>
                    <div class="paintball-icon"></div>
                </div>
            </div>
        </div>

        <div class="top-right-panel">
            <div class="top-right-container" id="top-right-ui-area">
                <div class="icon-row">
                    <img src="game_module/ui/ev_shadow_FILL0_wght700_GRAD0_opsz48.svg">
                    <img src="game_module/ui/tenancy_FILL0_wght700_GRAD0_opsz48.svg">
                    <img src="game_module/ui/fluorescent_FILL0_wght700_GRAD0_opsz48.svg">
                    <img src="game_module/ui/database_FILL0_wght700_GRAD0_opsz48.svg">
                    <img src="game_module/ui/barcode_scanner_FILL0_wght700_GRAD0_opsz48.svg">
                </div>
            </div>
        </div>

        <div class="mission-panel">
            <div class="mission-content" id="top-left-ui-area">
                <div class="mission-header">
                    <img class="icon" src="game_module/ui/double_arrow_FILL0_wght700_GRAD0_opsz48.svg">
                    <div class="mission-title">Kill'm all</div>
                </div>
                <div class="mission-description">
                    <img class="icon" src="game_module/ui/token_FILL0_wght700_GRAD0_opsz48.svg">
                    <div class="mission-text">Jump around and stuff</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="importmap">
    {
        "imports": {
            "three": "https://cdnjs.cloudflare.com/ajax/libs/three.js/0.172.0/three.module.min.js",
            "addons/": "/game_module/js/addons/"
        }
    }
</script>
<script type="module" src="game_module/js/game.js"></script>
