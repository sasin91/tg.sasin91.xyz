<?php

require_once __DIR__ . '/runtime/WebsocketServer.php';

$publisher = new Redis();
$publisher->connect('127.0.0.1', 6379);

$subscriber = new Redis();
$subscriber->pconnect('127.0.0.1', 6379);
$subscriber->setOption(Redis::OPT_READ_TIMEOUT, 0);

$storage = new Redis();
$storage->connect('127.0.0.1', 6379);

$http_server = new WebsocketServer(
    $publisher,
    $subscriber,
    $storage
);

$http_server->listen();

echo 'Listening on 127.0.0.1:8085' . PHP_EOL;