<?php

trait PubSubMessaging
{
    /**
     * Messages that should be broadcasted back to the clients
     * 
     * @var array
     */
    protected array $loopback_messages = [];

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
        $this->loopback_messages[] = ['channel' => $channel, 'message' => $message];

        $published = fwrite($this->redis_socket, "PUBLISH {$channel} {$message}");

        if ($published === false) {
            error_log("Failed publishing message");
        }
    }

    protected function subscribeToEvents(): void
    {
        $subscribed = fwrite($this->redis_socket, "SUBSCRIBE user_status chat_messages");
        if ($subscribed === false) {
            error_log("Error subscribing");
            return;
        }

        echo "subscribed \n";

        $fiber = new Fiber(function ($socket) {
            while (true) {
                $line = fgets($socket);

                if ($line) {
                    var_dump($line);
                }
                if (is_string($line) && preg_match('/\{.*?\}/', $line, $matches)) {
                    $this->broadcast($matches[0]);
                }

                $count = count($this->loopback_messages);
                while($count--) {
                    $message = $this->loopback_messages[$count];

                    $this->broadcast($message['channel'], $message['message']);
                }
                $this->loopback_messages = [];

                Fiber::suspend();
            }
        });

        $fiber->start($this->redis_socket);
        $this->fibers->enqueue($fiber);
    }

    protected function broadcast(string $channel, string $message): void
    {
        $client_sockets = array_column($this->clients, 'socket');

        $frame = $this->encodeWebSocketFrame(json_encode([
            'channel' => $channel,
            'message' => json_decode($message)
        ]));
    
        foreach ($client_sockets as $client) {
            $this->fwrite($client, $frame);
        }
    }

    /**
     * Safely attempt to write the message
     * 
     * @param resource $fp 
     * @param string $data 
     * @return bool 
     */
    protected function fwrite($fp, string $data): bool
    {
        $totalBytes = strlen($data);
        $writtenBytes = 0;
        $maxRetries = 5;
        $retries = 0;
    
        while ($writtenBytes < $totalBytes && $retries < $maxRetries) {
            $result = fwrite($fp, substr($data, $writtenBytes));
    
            if ($result === false) {
                error_log("Error writing to socket.");
                return false;
            } elseif ($result === 0) {
                error_log("Write returned 0 bytes; connection may be closed.");
                return false;
            }
    
            $writtenBytes += $result;
    
            if ($writtenBytes < $totalBytes) {
                $retries++;
                usleep(100000);
            }
        }
    
        if ($writtenBytes < $totalBytes) {
            error_log("Failed to write all bytes after retries.");
            return false;
        }
    
        return true;
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