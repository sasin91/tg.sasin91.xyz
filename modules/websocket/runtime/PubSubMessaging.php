<?php

trait PubSubMessaging
{
    /**
     * The redis socket used for sharing state across instances.
     *
     * @var resource
     */
    protected $redis_socket;

    protected function publishUserStatus($userId, $status): void
    {
        $this->publish('user_status', json_encode([
            'user_id' => $userId,
            'status' => $status,
        ]));
    }

    protected function publishChatMessage($user, $message): void
    {
        $this->publish('chat_message', json_encode([
            'user' => $user,
            'message' => $message,
        ]));
    }

    protected function publish(string $channel, string $message): void
    {
        $fiber = new Fiber(function (string $channel, string $message) {
            return fwrite($this->redis_socket, "PUBLISH {$channel} {$message}\r\n");
        });

        $fiber->start($channel, $message);
        $this->fibers->enqueue($fiber);
    }

    protected function subscribeToEvents(): void
    {
        fwrite($this->redis_socket, "SUBSCRIBE user_status\r\n");
        fwrite($this->redis_socket, "SUBSCRIBE chat_message\r\n");

        $fiber = new Fiber(function ($socket) {
            while ($line = fgets($socket)) {
                if (str_contains($line, 'message')) {
                    list(, , $channel, $message) = explode(' ', trim($line), 4);
                    echo "Received message on channel {$channel}: {$message}\n";

                    $this->broadcast($message);
                }

                Fiber::suspend();
            }
        });

        $fiber->start($this->redis_socket);
        $this->fibers->enqueue($fiber);
    }

    protected function broadcast($message): void
    {
        foreach ($this->clients as $client) {
            fwrite($client, $message);
        }
    }

    protected function establishRedisConnection(string $redis_host, int $redis_port): void
    {
        $this->redis_socket = stream_socket_client("tcp://{$redis_host}:{$redis_port}", $redis_errno, $redis_errstr);

        if (!$this->redis_socket) {
            throw new RuntimeException("Could not connect to Redis: $redis_errstr ($redis_errno)");
        }

        stream_set_blocking($this->redis_socket, false);
    }
}