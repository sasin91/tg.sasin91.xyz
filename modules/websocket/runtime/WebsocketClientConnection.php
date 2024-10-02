<?php

trait WebsocketClientConnection
{
    /**
     * Accepts new client connections and initializes the client session.
     *
     * @return void
     *
     * @throws Throwable if an error occurs during client acceptance or initialization
     */
    public function acceptClientConnection(): void
    {
        $client = @stream_socket_accept($this->server_socket, $this->timeout);

        if ($client) {
            $client_id = (int)$client;

            $this->clients[$client_id] = [
                'socket' => $client,
                'last_pong' => time(),
                'last_ping' => time(),
                'trongateToken' => null,
                'user_id' => null
            ];

            stream_set_blocking($client, false);
            $this->initiateClientConnection($client, $client_id);
            $this->listenForWebsocketFrames($client, $client_id);
            $this->keepAlive($client_id);
        }
    }

    /**
     * @param $client
     * @param int $client_id
     * @return void
     * @throws Throwable
     */
    protected function initiateClientConnection($client, int $client_id): void
    {
        $fiber = new Fiber(function ($client, $client_id) {
            $header_string = '';

            // Non-blocking socket, loop until headers are read
            while (!feof($client)) {
                $data = fread($client, 1024);
                if ($data === false) {
                    // No data, suspend the fiber and wait for more
                    Fiber::suspend();
                }
                $header_string .= $data;

                if (str_contains($header_string, "\r\n\r\n")) {
                    break;
                }
            }

            if (str_contains($header_string, "Upgrade: websocket")) {
                $this->handleWebSocketHandshake($client, $header_string);
            }

            // parse query params
            preg_match('/GET\s.*\?(.*)\sHTTP/', $header_string, $matches);
            parse_str($matches[1], $queryParams);

            $token = $queryParams['trongateToken'] ?? null;
            $userId = $queryParams['user_id'] ?? null;

            $this->clients[$client_id]['trongateToken'] = $token;
            $this->clients[$client_id]['user_id'] = $userId;

            $this->publishUserStatus($userId, 'online');

            // Client initiated,
            // terminate fiber
            return;
        });

        $fiber->start($client, $client_id);
        $this->fibers->enqueue($fiber);
    }

    /**
     * Listen for websocket frames from a client and process them
     *
     * @param resource $client The client socket
     * @param int $client_id The client ID
     * @return void
     * @throws Throwable
     */
    protected function listenForWebsocketFrames($client, int $client_id): void
    {
        $fiber = new Fiber(function ($client, $client_id) {
            while (is_resource($client) && !feof($client)) {
                $read = [$client];
                $write = $except = null;
                $available_streams = stream_select($read, $write, $except, 0, 200000);
                
                if ($available_streams) {
                    $frame = fread($client, 1024);
                    $decoded = $this->decodeWebSocketFrame($frame);

                    if (!empty($decoded)) {
                        if (isset($decoded['type']) && $decoded['type'] === 'pong') {
                            $this->clients[$client_id]['last_pong'] = time();
                            break;
                        }

                        $decodedMessage = $decoded['payload'];
                        $json = @json_decode($decodedMessage, true);
                        $response = $this->processWebSocketRequest($json, $client_id);
                        $responseFrame = $this->encodeWebSocketFrame($response);
                        $this->fwrite($client, $responseFrame);
                    }
                }

                // Yield back control after each iteration
                Fiber::suspend();
            }
        });

        $fiber->start($client, $client_id);
        $this->fibers->enqueue($fiber);
    }

    protected function processWebSocketRequest($json, $client_id)
    {
        $module = $json['module'] ?? null;
        $controller = $json['controller'] ?? ucwords($module);
        $action = $json['action'] ?? '_on_websocket_message';

        $controller_path = MODULES_ROOT . '/' . $module . '/controllers/' . $controller . '.php';
        if (file_exists($controller_path)) {
            require_once $controller_path;
            $controllerInstance = new $controller($module);
            return $controllerInstance->$action($json, $this->clients[$client_id]);
        }

        return 'Invalid request';
    }

    /**
     * keep the client alive using ping / pong packets
     *
     * @param int $client_id The ID of the client to keep alive
     * @return void
     * @throws Throwable If there is an error during execution
     */
    protected function keepAlive(int $client_id): void
    {
        $fiber = new Fiber(function () use ($client_id) {
            // @see https://developer.mozilla.org/en-US/docs/Web/API/WebSockets_API/Writing_WebSocket_servers#pings_and_pongs_the_heartbeat_of_websockets
            $pingFrame = $this->encodeWebSocketFrame('', 0x9);

            while (isset($this->clients[$client_id])) {
                $client = $this->clients[$client_id];
                $currentTime = time();
                $pong_diff = $currentTime - $client['last_pong'];
                $ping_diff = $currentTime - $client['last_ping'];

                if (
                    $pong_diff >= $this->pingTimeout 
                    // Prevent spamming pings
                    && $ping_diff > ($this->pingTimeout / 2)
                ) {
                    // @see https://developer.mozilla.org/en-US/docs/Web/API/WebSockets_API/Writing_WebSocket_servers#pings_and_pongs_the_heartbeat_of_websockets
                    $pingFrame = $this->encodeWebSocketFrame('', 0x9);
                    $ping = @fwrite(
                        $client['socket'], 
                        $pingFrame
                    );

                    if ($ping === false) {
                        $this->userOffline($client);
                        // Terminate fiber
                        return;
                    }

                    $this->clients[$client_id]['last_ping'] = time();
                }

                Fiber::suspend();
            }
        });

        $fiber->start();
        $this->fibers->enqueue($fiber);
    }

    protected function userOffline(array $client): void {
        fclose($client['socket']);
        unset($this->clients[(int)$client]);
        $this->publishUserStatus($client['user_id'], 'offline');
    }
}