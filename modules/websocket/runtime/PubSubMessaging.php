<?php

trait PubSubMessaging
{
    /**
     * The redis socket used for publishing state across instances.
     *
     * @var resource
     */
    protected $publisher;

    /**
     * The redis socket used for subscribing to state across instances.
     *
     * @var resource
     */
    protected $subscriber;

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
        if (!is_resource($this->publisher) || feof($this->publisher)) {
            error_log("Socket is closed. Attempting to reconnect...");
            $this->establishPublisherConnection();
        }

        $published = fwrite($this->publisher, $command = "PUBLISH {$channel} '{$message}'\r\n");

        if ($published === false) {
            error_log("Failed to publish message");
        } elseif ($published < strlen($command)) {
            error_log("Partial write detected");
        }
    }

    protected function subscribeToEvents(): void
    {
        $user_status = new Fiber(function ($socket) {
            $subscribed = fwrite($this->subscriber, "SUBSCRIBE user_status \r\n");
            if ($subscribed === false) {
                error_log("Error subscribing to user_status");
                return;
            }

            while (is_resource($socket) && !feof($socket)) {
                $read = [$socket];
                $write = $except = null;
                $available_streams = stream_select($read, $write, $except, 0, 200000);

                if ($available_streams !== false) {
                    $line = fread($socket, 1024);

                    if (is_string($line) && preg_match('/\{.*?\}/', $line, $matches)) {
                        $this->broadcast('user_status', $matches[0]);
                    }
                }

                Fiber::suspend();
            }
        });

        $user_status->start($this->subscriber);
        $this->fibers->enqueue($user_status);
    }

    protected function broadcast(string $channel, string $message): void
    {
        $client_sockets = array_column($this->clients, 'socket');

        $frame = $this->encodeWebSocketFrame(json_encode([
            'channel' => $channel,
            'message' => json_decode($message)
        ]));
    
        foreach ($client_sockets as $client) {
            if (!is_resource($client)) {
                continue;
            }

            $written = @fwrite($client, $frame);

            if ($written === false) {
                $this->userOffline($client);
            }
        }
    }

    protected function establishPublisherConnection(): void
    {
        $this->publisher = stream_socket_client("tcp://{$this->redis_host}:{$this->redis_port}", $publisher_errno, $publisher_errstr);

        if (!$this->publisher) {
            throw new RuntimeException("Could not connect to Publisher: $publisher_errstr ($publisher_errno)");
        }

        stream_set_blocking($this->publisher, false);
    }

    protected function establishSubscriberConnection(): void {
        $this->subscriber = stream_socket_client("tcp://{$this->redis_host}:{$this->redis_port}", $subscriber_errno, $subscriber_errstr);

        if (!$this->subscriber) {
            throw new RuntimeException("Could not connect to Subscriber: $subscriber_errstr ($subscriber_errno)");
        }

        stream_set_blocking($this->subscriber, false);
    }
}