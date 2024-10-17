<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/runtime/WebsocketServer.php';

$server = new WebsocketServer(
    redis_host: REDIS_HOST,
    redis_port: REDIS_PORT,
);

$server->listen();