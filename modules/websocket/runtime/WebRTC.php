<?php

class WebRTC
{
    public function __construct(
        private readonly WebsocketServer $server,
    )
    {
    }

    public function call(array $json, array $client): string {
        $payload = $json['payload'];

        switch ($json['intent']) {
            case 'start':
                $this->server->broadcast('webrtc', json_encode([
                    'intent' => 'start',
                    'payload' => $payload,
                ]));
                return 'OK';

            case 'offer':
            case 'answer':
            case 'candidate':
                if (isset($this->clients[$json['target']])) {
                    $target_client = $this->clients[$json['target']];
                    $this->fwrite($target_client['socket'], json_encode($json));
                }
                break;
            case 'register':
                // no-op; already registered.
                break;
        }
    }
}