<?php

require_once __DIR__ . '/runtime/WebsocketServer.php';

$server = new WebsocketServer();

$server->listen();