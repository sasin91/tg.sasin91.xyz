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
        // Accept new client connections
        $client = @stream_socket_accept($this->server_socket, $this->timeout);

        if ($client) {
            $client_id = (int)$client;

            $this->clients[$client_id] = [
                'socket' => $client,
                'last_pong' => time(),
                'trongateToken' => null,
                'user_id' => null
            ];

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
            stream_set_blocking($client, false);
            $header_string = '';

            // Read HTTP request for WebSocket handshake
            while (!feof($client)) {
                $data = fread($client, 1024);
                if ($data === false) {
                    // No data, suspend the fiber and wait for more
                    Fiber::suspend();
                }
                $header_string .= $data;

                // End of headers (double CRLF) or the entire request is small
                if (str_contains($header_string, "\r\n\r\n")) {
                    break;
                }
            }

            // Handle WebSocket handshake if Upgrade request is found
            if (str_contains($header_string, "Upgrade: websocket")) {
                $this->handleWebSocketHandshake($client, $header_string);
            }

            preg_match('/GET\s.*\?(.*)\sHTTP/', $header_string, $matches);
            parse_str($matches[1], $queryParams);

            $token = $queryParams['trongateToken'] ?? null;
            $userId = $queryParams['user_id'] ?? null;

            $this->clients[$client_id]['trongateToken'] = $token;
            $this->clients[$client_id]['user_id'] = $userId;

            $this->storeClient($client_id);
            $this->publishUserStatus($userId, 'online');

            // Client initiated,
            // terminate fiber
            return;
        });

        $fiber->start($client, $client_id);
        $this->fibers->enqueue($fiber);
    }

    /**
     * Persist the client for other instances to retrieve
     *
     * @param int $client_id
     * @return void
     * @throws Throwable
     */
    protected function storeClient(int $client_id): void
    {
        $fiber = new Fiber(function ($client_id, $client_socket) {
            $this->storage->set("client:$client_id", $client_socket);
        });

        $fiber->start($client_id, $this->clients[$client_id]);
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
            while (!feof($client)) {
                $frame = fread($client, 1024);
                $decoded = $this->decodeWebSocketFrame($frame);

                if (!empty($decoded)) {
                    $decodedMessage = $decoded['payload'];

                    $json = json_decode($decodedMessage, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $responseFrame = $this->encodeWebSocketFrame('Invalid JSON payload.');
                    } else {
                        $module = $json['module'];
                        $controller = $json['controller'] ?? ucwords($module);
                        $action = $json['action'] ?? '_on_websocket_message';

                        // TODO: instead of invoking the code, should we dispatch a curl HTTP request here?
                        $controller_path = MODULES_ROOT . '/' . $module . '/controllers/' . $controller . '.php';
                        require_once $controller_path;
                        $controller = new $controller($module);
                        $payload = $controller->$action(
                            $json,
                            $this->clients[$client_id]
                        );
                        $responseFrame = $this->encodeWebSocketFrame($payload);
                    }

                    fwrite($client, $responseFrame);
                }

                // Yield back control after each iteration
                Fiber::suspend();
            }
        });

        $fiber->start($client, $client_id);
        $this->fibers->enqueue($fiber);
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
        $fiber = new Fiber(function (int $clientId) {
            $currentTime = time();
            $client_state = $this->clients[$clientId];
            $last_pong = (int)$client_state['last_pong'];
            $client_socket = $client_state['socket'];

            while(true) {
                // Check if we need to send a ping
                if (($currentTime - $last_pong) >= $this->pingInterval) {
                    fwrite($client_socket, "ping\n");
                }

                // Check if pong was not received within the timeout
                if (($currentTime - $last_pong) >= $this->pongTimeout) {
                    fclose($client_socket);

                    $userId = $this->clients[$clientId]['user_id'];
                    $this->publishUserStatus($userId, 'offline');
                    unset($this->clients[$clientId]);

                    return;
                }

                Fiber::suspend();
            }
        });

        $fiber->start($client_id);
        $this->fibers->enqueue($fiber);
    }
}