<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/trongate.css">
	<link rel="stylesheet" href="<?= BASE_URL ?>css/app.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>game_module/css/game.css">
    <script type="importmap">
        {
          "imports": {
            "three": "https://unpkg.com/three@0.158.0/build/three.module.js",
            "three/addons/": "https://unpkg.com/three@0.158.0/examples/jsm/"
          }
        }
      </script>

    <title>Game</title>
</head>

<body>
    <main id="container" data-assets-path="<?= BASE_URL ?>game_module"></main>
    <script type="module" src="<?= BASE_URL ?>game_module/js/game.js"></script>
</body>