<?php

trait PubSubMessaging
{
    private function publishUserStatus($user, $status): void
    {
        $fiber = new Fiber(function ($userId, $status) {
            $this->publisher->publish('user_status', json_encode([
                'user_id' => $userId,
                'status' => $status,
            ]));
        });

        $fiber->start($user, $status);
        $this->fibers->enqueue($fiber);
    }

    private function publishChatMessage($user, $message): void
    {
        $fiber = new Fiber(function ($user, $message) {
            $this->publisher->publish('chat_messages', json_encode([
                'user' => $user,
                'message' => $message,
            ]));
        });

        $fiber->start($user, $message);
        $this->fibers->enqueue($fiber);
    }

    private function subscribeToEvents(): void
    {
        $fiber = new Fiber(function () {
            try {
                $this->subscriber->subscribe(['user_status', 'chat_message'], function ($publisher, $channel, $message) {
                    $this->broadcast($message);

                    Fiber::suspend();
                });
            } catch (RedisException $redisException) {
                error_log('RedisException: ' . $redisException->getMessage());
            }
        });

        $fiber->start();
        $this->fibers->enqueue($fiber);
    }

    private function broadcast($message): void
    {
        foreach ($this->clients as $client) {
            fwrite($client, $message);
        }
    }
}