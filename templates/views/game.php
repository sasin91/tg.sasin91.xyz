<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?= BASE_URL ?>css/trongate.css">
	<link rel="stylesheet" href="<?= BASE_URL ?>css/app.css">
	<!-- don't change anything above here -->
	<!-- add your own stylesheet below here -->
	<title>Game</title>
</head>

<body>
<main class="container"><?= Template::display($data) ?></main>
<script src="<?= BASE_URL  ?>js/app.js"></script>
</body>