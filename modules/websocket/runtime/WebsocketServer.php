<?php

require_once __DIR__ . '/WebsocketHandshake.php';
require_once __DIR__ . '/WebsocketFrameEncoding.php';
require_once __DIR__ . '/PubSubMessaging.php';

const APP_ROOT = __DIR__ . '/../../../';
const MODULES_ROOT = APP_ROOT . 'modules';
const ENGINE_ROOT = APP_ROOT . 'engine';

spl_autoload_register(function ($class_name) {

    $class_name = str_replace('alidation_helper', 'alidation', $class_name);
    $target_filename = realpath(ENGINE_ROOT . '/' . $class_name . '.php');

    if (file_exists($target_filename)) {
        return require_once($target_filename);
    }

    return false;
});

class WebsocketServer
{
    use WebsocketHandshake;
    use WebsocketFrameEncoding;
    use PubSubMessaging;

    /**
     * The server socket used for handling incoming connections.
     *
     * @var resource
     */
    private $server_socket;

    /**
     * List of client sockets and the last pong time
     * Keyed by resource ID
     *
     * @TODO: This may get quite large, i may need to reduce memory footprint
     * @var array<int, ['socket' => resource, 'last_pong' => int]>
     */
    protected array $clients = [];

    /**
     * The queue of connection handlers.
     * They're processed in order of First in, First out
     *
     * @var SplQueue
     */
    private SplQueue $fibers;

    /**
     * Whether the program is running or not.
     *
     * @var bool $running False indicates the program is not running, True indicates the program is running.
     */
    public bool $running = false;

    public function __construct(
        // Publish events for other instances or apps to subscribe to
        private readonly Redis $publisher,
        // Listen for events from other instances
        private readonly Redis $subscriber,
        // Data storage
        private readonly Redis $storage,
        string                 $host = '127.0.0.1',
        int                    $port = 8085,
        private readonly int   $timeout = 0,
        private readonly int   $pingInterval = 5,
        private readonly int   $pongTimeout = 10,
    )
    {
        // Create a TCP socket
        $this->server_socket = stream_socket_server(
            "tcp://{$host}:{$port}",
            $errno,
            $errstr
        );

        if (!$this->server_socket) {
            throw new RuntimeException('Unable to create socket: ' . $errstr);
        }

        pcntl_async_signals(true);

        $this->fibers = new SplQueue();

        $this->subscribeToEvents();
    }

    public function listen(): void
    {
        $this->running = true;

        while ($this->running) {
            $available_streams = $this->selectStreams();

            pcntl_signal_dispatch();

            if (false === $available_streams) {
                echo "no streams available";
                // if a system call has been interrupted,
                // we cannot rely on it's outcome
                return;
            }

            $this->acceptClientConnection();
            $this->dispatchFibers();
        }
    }

    public function selectStreams(): int|false
    {
        $timeout = $this->fibers->count() > 0 ? $this->timeout : null;
        $client_sockets = array_column($this->clients, 'socket');
        $read = [$this->server_socket, ...$client_sockets];
        $write = null;
        $except = null;

        /** @var ?callable $previous */
        $previous = set_error_handler(function ($errno, $errstr) use (&$previous) {
            // suppress warnings that occur when `stream_select()` is interrupted by a signal
            // PHP defines `EINTR` through `ext-sockets` or `ext-pcntl`, otherwise use common default (Linux & Mac)
            $eintr = \defined('SOCKET_EINTR') ? \SOCKET_EINTR : (\defined('PCNTL_EINTR') ? \PCNTL_EINTR : 4);
            if ($errno === \E_WARNING && str_contains($errstr, '[' . $eintr . ']: ')) {
                return false;
            }

            // forward any other error to registered error handler or print warning
            return ($previous !== null) ? \call_user_func_array($previous, \func_get_args()) : false;
        });

        try {
            $available_streams = stream_select($read, $write, $except, $timeout === null ? null : 0, $timeout);
            restore_error_handler();

            return $available_streams;
        } catch (\Throwable $e) {
            restore_error_handler();
            error_log($e->getMessage());
        }

        return false;
    }

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
                    $headers = $this->parseHeaders($header_string);
                }

                preg_match('/GET\s.*\?(.*)\sHTTP/', $header_string, $matches);
                parse_str($matches[1], $queryParams);

                $token = $queryParams['trongateToken'] ?? null;
                $userId = $queryParams['user_id'] ?? null;

                $this->clients[$client_id]['trongateToken'] = $token;
                $this->clients[$client_id]['user_id'] = $userId;

                $this->storeClient($client_id);
                $this->publishUserStatus($userId, 'online');
                $this->keepAlive($client_id);

                // After the handshake, switch to WebSocket frame handling
                while (!feof($client)) {
                    $frame = fread($client, 1024);
                    if (empty($frame)) {
                        Fiber::suspend();
                    }

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

                    Fiber::suspend();
                }
            });

            $fiber->start($client, $client_id);
            $this->fibers->enqueue($fiber);
        }
    }

    private function keepAlive(int $client_id): void
    {
        $fiber = new Fiber(function (int $clientId) {
            $currentTime = time();
            $client_state = $this->clients[$clientId];
            $last_pong = (int)$client_state['last_pong'];
            $client_socket = $client_state['socket'];

            // Check if we need to send a ping
            if (($currentTime - $last_pong) >= $this->pingInterval) {
                fwrite($client_socket, "ping\n");
                Fiber::suspend();
            }

            // Check if pong was not received within the timeout
            if (($currentTime - $last_pong) >= $this->pongTimeout) {
                fclose($client_socket);

                $userId = $this->clients[$clientId]['user_id'];
                $this->publishUserStatus($userId, 'offline');
                unset($this->clients[$clientId]);
            }
        });

        $fiber->start($client_id);
        $this->fibers->enqueue($fiber);
    }

    /**
     * Process the queued fibers
     *
     * @return void
     */
    private function dispatchFibers(): void
    {
        $count = $this->fibers->count();

        while ($count--) {
            $fiber = $this->fibers->dequeue();

            if ($fiber->isSuspended()) {
                $fiber->resume();
            }

            if (!$fiber->isTerminated()) {
                $this->fibers->enqueue($fiber);
            }
        }
    }

    /**
     * Persist the client for other instances to retrieve
     *
     * @param int $client_id
     * @return void
     * @throws Throwable
     */
    public function storeClient(int $client_id): void
    {
        $fiber = new Fiber(function ($client_id, $client_socket) {
            $this->storage->set("client:$client_id", $client_socket);
        });

        $fiber->start($client_id, $this->clients[$client_id]);
        $this->fibers->enqueue($fiber);
    }
}