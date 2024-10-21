<?php

trait Events {
    /**
     * Registers the client and notifies listening clients.
     *
     * @param integer $client_id
     * @param string $fingerprint
     * @param string|null $token
     * @param integer $user_id
     * @return void
     */
    protected function emit_user_online(int $client_id, string $fingerprint, ?string $token, int $user_id): void {
        $this->clients[$client_id]['fingerprint'] = $fingerprint;
        $this->clients[$client_id]['trongateToken'] = $token;
        $this->clients[$client_id]['user_id'] = $user_id;

        $this->publish_user_status($user_id, 'online');
    }

    /**
     * Removes the client from the list of active clients and notifies listening clients.
     *
     * @param array $client
     * @return void
     */
    protected function emit_user_offline(array $client): void {
        if (is_resource($client['socket'])) {
            fclose($client['socket']);
        }
        unset($this->clients[(int)$client]);
        $this->publish_user_status($client['user_id'], 'offline');
    }
}