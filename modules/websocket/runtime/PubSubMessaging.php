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
        $user_status = new Fiber($this->jsonSubscription(
            'user_status',
            $this->broadcast(...)
        ));
        $user_status->start($this->subscriber);
        $this->fibers->enqueue($user_status);

        $live_streams = new Fiber($this->jsonSubscription(
            'live_streams',
            $this->broadcast(...)
        ));
        $live_streams->start($this->subscriber);
        $this->fibers->enqueue($live_streams);
    }

  /**
    * Parses a Redis subscribe response and returns the channel and payload.
    *
    * @param string $response The raw response from the Redis server.
    * @return array|false An array containing the channel and payload, or false if the response is invalid.
  **/
  protected function parseRedisSubscribeResponse(string $response) {
    $lines = explode("\r\n", $response);
    
    // Redis subscribe responses follow this structure:
    // [0] => *3 (number of elements in the response)
    // [1] => $7 (message type)
    // [2] => message (keyword for a subscribed message)
    // [3] => $12 (length of the channel name)
    // [4] => live_streams (the channel name)
    // [5] => $24 (length of the payload)
    // [6] => {"status":"new","id":17} (the actual payload)
 
    if (count($lines) < 7 || $lines[0] !== '*3') {
        return false;
    }
    
    $channel = $lines[4];
    $payload = $lines[6];
    
    return [
        $channel,
        $payload,
    ];
  }

    /**
     * Creates a subscription to a given channel and listens for incoming JSON messages.
     *
     * @param string $channel The channel name to subscribe to.
     * @param callable $on_message
     * @return Closure A closure that handles the subscription process and listens for messages on the given channel.
     */
    private function jsonSubscription(string $channel, callable $on_message): Closure
    {
        return function ($socket) use ($on_message, $channel) {
            $subscribed = fwrite($this->subscriber, "SUBSCRIBE $channel \r\n");
            if ($subscribed === false) {
                error_log("Error subscribing to $channel");
                return;
            }

            while (is_resource($socket) && !feof($socket)) {
                $read = [$socket];
                $write = $except = null;
                $available_streams = stream_select($read, $write, $except, 0, 200000);

                if ($available_streams !== false) {
                    $line = fread($socket, 1024);

                    if (is_string($line) && preg_match('/\{.*?}/', $line, $matches)) {           
                      $parsed = $this->parseRedisSubscribeResponse($line);
                    
                      if ($parsed === false) {
                        break;
                      }
 
                      [$channel, $payload] = $parsed;
                      if ($matches[0] !== $payload) {
                        echo "Payload mismatch: $matches[0] !== $payload\n";
                      }
                      $on_message($channel, $payload); 
                    }
                }

                Fiber::suspend();
            }
        };
    }

    protected function broadcast(string $channel, string $message): void
    {
        $frame = $this->encodeWebSocketFrame(json_encode([
            'channel' => $channel,
            'message' => json_decode($message)
        ]));
    
        foreach ($this->clients as $client) {
            if (!is_resource($client['socket'])) {
                continue;
            }

            $written = @fwrite($client['socket'], $frame);

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
