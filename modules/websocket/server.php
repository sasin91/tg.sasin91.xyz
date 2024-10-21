<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/runtime/Websocket_server.php';

$server = new Websocket_server(
    redis_host: REDIS_HOST,
    redis_port: REDIS_PORT,
);

$server->listen();