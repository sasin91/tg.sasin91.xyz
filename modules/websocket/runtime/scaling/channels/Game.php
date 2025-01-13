<?php

class Game extends Channel {
	public array $players = [];

	public function on_message(Client $client, array $message): ?string {
		$payload = (array)$message['payload'];
		$server = (string)$message['server'];

		// TODO: verify and validate the player can actually perform the event
		switch ($message['event']) {
			case 'player:spawn':
				$this->players[$client->id] = array_merge(
                    $payload,
                    ['server' => $server]
                );
				
				$this->messenger->broadcast(
					"game:$server", 
					json_encode([
						'event' => 'player:online',
						'player' => $payload,
						'payload' => []
					])
				);
				break;
			case 'player:cast':
				$player = $this->find_player($payload['id']);

				if (is_array($player)) {
					$this->messenger->broadcast(
						"game:$server", 
						json_encode([
							'event' => 'player:cast',
							'player' => $player,
							'payload' => $payload
						])
					);
				}
				break;
			case 'player:move':
				$player = $this->find_player($payload['id']);

				if (is_array($player)) {
					$this->messenger->broadcast(
						"game:$server", 
						json_encode([
							'event' => 'player:move',
							'player' => $player,
							'payload' => $payload
						])
					);
				}
				break;
		}

        return null;
	}

	public function on_offline(Client $client): void {
		$player = $this->players[$client->id];
        $server = $player['server'];

		// TODO: persist state

		unset($this->players[$client->id]);

		$this->messenger->broadcast(
			"game:$server",
			json_encode([
				'event' => 'player:offline',
				'player' => $player,
				'payload' => []
			])
		);
	}

	private function find_player(int $id): ?array {
		foreach ($this->players as $player) {
			if ((int)$player['id'] === $id) {
				return $player;
			}
		}

		return null;
	}
}