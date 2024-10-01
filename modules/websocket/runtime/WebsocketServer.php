<?php

require_once __DIR__ . '/WebsocketHandshake.php';
require_once __DIR__ . '/WebsocketFrameEncoding.php';
require_once __DIR__ . '/WebsocketClientConnection.php';
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
    use WebsocketClientConnection;
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
        string               $host = '127.0.0.1',
        int                  $port = 8085,
        private readonly int $timeout = 0,
        private readonly int $pingInterval = 5,
        private readonly int $pongTimeout = 10,
        string               $redis_host = '127.0.0.1',
        int                  $redis_port = 6379,
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

        $this->establishRedisConnection($redis_host, $redis_port);
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
}
