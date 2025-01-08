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
                        <div class="latency"><?= number_format($player->latency, 0) ?>ms</div>
                        <div class="latency-tooltip">Ping</div>
                    </div>
                    <div class="paintball-icon"></div>
                </div>
            </div>
        </div>

        <div class="top-right-panel">
            <div class="top-right-container" id="top-right-ui-area">
                <div class="icon-row">
                    <!-- START: ev_shadow_FILL0_wght700_GRAD0_opsz48.svg -->
                    <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path d="M24 43.15q-3.95 0-7.45-1.5t-6.1-4.1q-2.6-2.6-4.1-6.1-1.5-3.5-1.5-7.45t1.5-7.45q1.5-3.5 4.1-6.1 2.6-2.6 6.1-4.1 3.5-1.5 7.45-1.5t7.45 1.5q3.5 1.5 6.1 4.1 2.6 2.6 4.1 6.1 1.5 3.5 1.5 7.45t-1.5 7.45q-1.5 3.5-4.1 6.1-2.6 2.6-6.1 4.1-3.5 1.5-7.45 1.5Zm-7.6-7.3q-1.45-2.35-2.2-5.3-.75-2.95-.75-6.2 0-3.65.925-6.85.925-3.2 2.675-5.65-3.4 1.7-5.45 4.975Q9.55 20.1 9.55 24q0 3.75 1.85 6.875t5 4.975Zm10.4 1.95q4.3-.5 7.4-3.6 3.1-3.1 3.6-7.4Zm-3.7 0 15.3-15.25q-.1-.8-.3-1.575-.2-.775-.45-1.525L21.25 35.8q.45.55.875 1.075.425.525.975.925Zm-3.05-4.45 16.5-16.5q-.35-.6-.75-1.15-.4-.55-.85-1.05l-16 16q.2.65.45 1.325.25.675.65 1.375Zm-1.6-5.85L33.1 12.8q-.55-.45-1.125-.8-.575-.35-1.175-.7L18.15 23.9q0 .95.075 1.85.075.9.225 1.75Zm.45-8.05 9.3-9.2q-.8-.3-1.675-.45-.875-.15-1.725-.2-2.35 1.35-3.875 3.9T18.9 19.45Z"/></svg>
                    <!-- END: ev_shadow_FILL0_wght700_GRAD0_opsz48.svg -->

                    <!-- START: tenancy_FILL0_wght700_GRAD0_opsz48.svg -->
                    <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path d="M6.8 44.75q-2.4 0-4.1-1.7Q1 41.35 1 39q0-2.4 1.7-4.1 1.7-1.7 4.1-1.7.25 0 .625.05t.775.2l9.35-14.65q-1.25-1.15-1.95-2.825-.7-1.675-.7-3.625 0-3.8 2.675-6.475T24 3.2q3.8 0 6.45 2.675Q33.1 8.55 33.1 12.35q0 1.95-.7 3.625T30.45 18.8l9.35 14.65q.4-.15.775-.2.375-.05.675-.05 2.35 0 4.05 1.7Q47 36.6 47 39q0 2.35-1.7 4.05-1.7 1.7-4.05 1.7-2.4 0-4.1-1.7-1.7-1.7-1.7-4.05 0-.9.35-1.775.35-.875.9-1.725l-9.35-14.7q-.35.2-.725.325t-.775.125V33.6q1.75.7 2.85 2.15T29.8 39q0 2.35-1.7 4.05-1.7 1.7-4.1 1.7-2.35 0-4.05-1.7-1.7-1.7-1.7-4.05 0-1.8 1.075-3.25t2.825-2.15V21.25q-.4 0-.775-.125T20.7 20.8l-9.4 14.7q.55.85.9 1.725.35.875.35 1.775 0 2.35-1.7 4.05-1.7 1.7-4.05 1.7Zm0-4.15q.65 0 1.125-.475T8.4 39q0-.7-.475-1.15-.475-.45-1.125-.45-.7 0-1.175.45-.475.45-.475 1.15 0 .65.475 1.125T6.8 40.6ZM24 17.3q2.1 0 3.525-1.45 1.425-1.45 1.425-3.5 0-2.1-1.425-3.525Q26.1 7.4 24 7.4q-2.05 0-3.5 1.425-1.45 1.425-1.45 3.525 0 2.05 1.45 3.5 1.45 1.45 3.5 1.45Zm0 23.3q.7 0 1.15-.475.45-.475.45-1.125 0-.7-.45-1.15-.45-.45-1.15-.45-.65 0-1.125.45T22.4 39q0 .65.475 1.125T24 40.6Zm17.25 0q.65 0 1.125-.475T42.85 39q0-.7-.475-1.15-.475-.45-1.125-.45-.7 0-1.175.45-.475.45-.475 1.15 0 .65.475 1.125t1.175.475Z"/></svg>
                    <!-- END: tenancy_FILL0_wght700_GRAD0_opsz48.svg -->

                    <!-- START: fluorescent_FILL0_wght700_GRAD0_opsz48.svg -->
                    <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path d="M9.55 30.45V17.5H38.5v12.95Zm12.5-22.1V1.7h4.1v6.65ZM39.2 13.9 36.3 11l4.2-4.15 2.85 2.85ZM22.05 46.3v-6.65h4.1v6.65ZM40.5 41l-4.2-4.2 2.9-2.85 4.15 4.15ZM8.85 13.9l-4.2-4.2 2.9-2.85L11.7 11ZM7.55 41l-2.9-2.9 4.2-4.15 2.85 2.85Zm6.7-15.25h19.5v-3.5h-19.5Zm0 0v-3.5 3.5Z"/></svg>
                    <!-- END: fluorescent_FILL0_wght700_GRAD0_opsz48.svg -->

                    <!-- START: database_FILL0_wght700_GRAD0_opsz48.svg -->
                    <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path d="M24 21.45q-8.25 0-13.4-2.65t-5.15-5.95q0-3.35 5.125-5.975Q15.7 4.25 24 4.25q8.25 0 13.4 2.625t5.15 5.975q0 3.3-5.125 5.95Q32.3 21.45 24 21.45Zm0 11.1q-7.5 0-13.025-2.35T5.45 24.5v-6.15q0 1.8 1.575 3.275 1.575 1.475 4.2 2.55 2.625 1.075 5.95 1.675 3.325.6 6.825.6t6.825-.575q3.325-.575 5.95-1.65t4.2-2.575q1.575-1.5 1.575-3.3v6.15q0 3.35-5.5 5.7T24 32.55Zm0 11.15q-7.5 0-13.025-2.35t-5.525-5.7V29.5q0 1.8 1.575 3.275 1.575 1.475 4.2 2.55 2.625 1.075 5.95 1.65T24 37.55q3.5 0 6.825-.575t5.95-1.625q2.625-1.05 4.2-2.525Q42.55 31.35 42.55 29.5v6.15q0 3.35-5.5 5.7T24 43.7Z"/></svg>
                    <!-- END: database_FILL0_wght700_GRAD0_opsz48.svg -->

                    <!-- START: barcode_scanner_FILL0_wght700_GRAD0_opsz48.svg -->
                    <svg xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path d="M.85 4.85H11.1V9H5v6.1H.85Zm36 0H47.1V15.1h-4.15V9h-6.1ZM42.95 39v-6.1h4.15v10.25H36.85V39ZM5 32.9V39h6.1v4.15H.85V32.9Zm8.7-21.4h2.1v24.95h-2.1Zm-6.05 0h4v24.95h-4Zm12.15 0h4.15v24.95H19.8Zm14.4 0h2.1v24.95h-2.1Zm4.15 0h1.9v24.95h-1.9Zm-12.3 0h6.05v24.95h-6.05Z"/></svg>
                    <!-- END: barcode_scanner_FILL0_wght700_GRAD0_opsz48.svg -->
                </div>
            </div>
        </div>

        <div class="mission-panel">
            <div class="mission-content" id="top-left-ui-area">
                <div class="mission-header">
                    <!-- START: double_arrow_FILL0_wght700_GRAD0_opsz48.svg -->
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path d="M9.4 38.85 20.45 24 9.4 9.15h5.85L26.3 24 15.25 38.85Zm14.75 0L35.2 24 24.15 9.15h5.8L41.05 24l-11.1 14.85Z"/></svg>
                    <!-- END: double_arrow_FILL0_wght700_GRAD0_opsz48.svg -->

                    <div class="mission-title">Kill'm all</div>
                </div>
                <div class="mission-description">
                    <!-- START: token_FILL0_wght700_GRAD0_opsz48.svg -->
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" height="48" width="48"><path d="M24 45.3 4.85 34.35v-21.3L24 2.7l19.15 10.35v21.3Zm-5.8-26.9q1.2-1.15 2.65-1.8 1.45-.65 3.15-.65t3.15.65q1.45.65 2.65 1.8l6.4-3.8L24 8.15 11.8 14.6Zm3.75 20.3v-6.95q-2.65-.7-4.325-2.8-1.675-2.1-1.675-4.95 0-.4.025-.875t.225-1.075l-6.65-4V31.6ZM24 27.95q1.65 0 2.8-1.175T27.95 24q0-1.65-1.15-2.8T24 20.05q-1.6 0-2.775 1.15-1.175 1.15-1.175 2.8 0 1.6 1.175 2.775Q22.4 27.95 24 27.95Zm2.05 10.75 12.4-7.1V18.05l-6.6 4q.15.6.175 1.075.025.475.025.875 0 2.85-1.675 4.95t-4.325 2.8Z"/></svg>
                    <!-- END: token_FILL0_wght700_GRAD0_opsz48.svg -->
                    <div class="mission-text">Jump around and stuff</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="game_module/js/game.js"></script>
