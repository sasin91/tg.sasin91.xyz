<?php

/**
 * @property-read Trongate_tokens $trongate_tokens
 */
class Game extends Trongate {
    public function index(): void {
        $this->module('trongate_tokens');

        $data['token'] = $this->trongate_tokens->_attempt_get_valid_token();

        if (!isset($_SESSION['player_id'])) {
            $_SESSION['player_id'] = uniqid();
        }

        $data['player'] = (object)[
            'id' => $_SESSION['player_id'],
            'health' => 100,
            'mana' => 100,
            'name' => 'Player',
            'latency' => 42,
            'x' => 0,
            'y' => 0,
            'z' => 0,
        ];

        $data['view_module'] = 'game';
        $data['view_file'] = 'index';

        $this->template('game', $data);
    }

    public function players(): void {
        echo json_encode([
            [
                'id' => 1,
                'name' => 'Player',
                'health' => 100,
                'mana' => 100,
                'latency' => 42,
            ],
            [
                'id' => 2,
                'name' => 'Player 1',
                'health' => 100,
                'mana' => 100,
                'latency' => 23,
            ]
        ]);
    }

    public function ping(): void {
        var_dump($_POST);
    }
}