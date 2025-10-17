<?php

class Live_streams extends Channel
{
    public function listen()
    {
        $subscriber = $this->messenger->get_subscriber();

        // Ensure connection is established
        if (!$subscriber->connection) {
            $subscriber->connect();
        }

        $fiber = new Fiber($this->json_subscription(
            $this->name(),
            function($channel, $message) {
                $this->messenger->broadcast($channel, $message);
            }
        ));

        $fiber->start($subscriber->connection);

        $this->fibers->enqueue($fiber);
    }
}
