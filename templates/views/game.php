<?php
$trongateToken = $token ?? $_SESSION["trongatetoken"] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="trongate-token" content="<?= $trongateToken ?>">
    <base href="<?= BASE_URL ?>">
    <title><?= OUR_NAME ?></title>
    <script type="importmap">
        {
            "imports": {
                "three": "https://cdnjs.cloudflare.com/ajax/libs/three.js/0.172.0/three.module.min.js",
                "addons/": "/game_module/js/addons/"
            }
        }
    </script>
    <link rel="preload" href="css/trongate.css" as="style" />
    <link rel="stylesheet" href="css/trongate.css" />
    <style>
        body {
            background: var(--background);
            margin: 0;
            background-color: #000;
            color: #fff;
            font-family: Monospace;
            font-size: 13px;
            line-height: 24px;
            overscroll-behavior: none;
        }
        header {
            border-radius: 100%;
            padding: 1rem;
            top: 0;
            flex: none;
        }
    </style>
    <link rel="preload" href="game_module/css/game.css" as="style" />
    <link rel="stylesheet" href="game_module/css/game.css" />
    <link rel="preload" href="websocket_module/js/websocket.js" as="script" />
    <link rel="modulepreload" href="game_module/js/game.js" />
</head>
<body>
<header>
    <nav>
        <a title="Back" href="<?= BASE_URL ?>">
            <svg height="1.5rem" width="1.5rem" data-slot="icon" fill="none" stroke-width="1.5" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"></path>
            </svg>
        </a>
    </nav>
</header>
<main>
    <?= Template::display($data) ?>
</main>

<script src="websocket_module/js/websocket.js"></script>
<script>
    window.socket = new Socket(
        `<?= WEBSOCKET_URL ?>?trongateToken=<?= $trongateToken ?>&user_id=<?= $user_id ?? null ?>`
    );
</script>
</body>
</html>
