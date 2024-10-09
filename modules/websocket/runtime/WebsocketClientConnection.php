<?php

trait WebsocketClientConnection
{
    /**
     * Lazily instantiated message handler instance
     * 
     * @var null|Trongate_controller_action
     */
    protected ?Trongate_controller_action $controller_action = null;

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
                'user_id' => null,
                'fingerprint' => null
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

            $fingerprint = $queryParams['fingerprint'];
            $token = $queryParams['trongateToken'] ?? null;
            $userId = $queryParams['user_id'] ?? null;

            $this->clients[$client_id]['fingerprint'] = $fingerprint;
            $this->clients[$client_id]['trongateToken'] = $token;
            $this->clients[$client_id]['user_id'] = $userId;

            $this->publishUserStatus($userId, 'online');

            $unique_clients = [];
            $num_clients = count($this->clients);

            foreach($this->clients as $client) {
                if (isset($unique_clients[$client['fingerprint']])) {
                    continue;
                }

                $unique_clients[$client['fingerprint']] = $client;
            }

            $this->broadcast('state', json_encode([
                'num_online' => max(0, count($unique_clients) - 1) // subtract our own connection
            ]));

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
                            continue;
                        }

                        $decodedMessage = $decoded['payload'];
                        $json = @json_decode($decodedMessage, true);
                        $response = $this->processWebSocketRequest($json ?? [], $client_id);
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

    protected function processWebSocketRequest(array $json, int $client_id): string
    {
        if (isset($json['module'])) {
            return $this->controller_action()->call($json, $this->clients[$client_id]);
        }

        if (isset($json['type'])) {
            switch ($json['type']) {
                case 'offer':
                case 'answer':
                case 'candidate':
                    if (isset($this->clients[$json['target']])) {
                        $target_client = $this->clients[$json['target']];
                        $this->fwrite($target_client['socket'], json_encode($json));
                    }
                    break;
                case 'register':
                    // no-op; already registered.
                    break;
            }
        }

        return 'Invalid request.';
    }

    protected function controller_action(): Trongate_controller_action
    {
        if (!$this->controller_action) {
            require_once __DIR__ . '/Trongate_controler_action.php';
            $this->controller_action = new Trongate_controller_action();
        }

        return $this->controller_action;   
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
                    if (!is_resource($client['socket'])) {
                        $this->userOffline($client);
                        // Terminate fiber
                        return;
                    }

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