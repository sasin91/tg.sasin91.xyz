<?php

/**
 * @property-read Trongate_tokens $trongate_tokens
 */
class Game extends Trongate {
    public function index(): void {
        $this->module('trongate_tokens');

        $data['token'] = $this->trongate_tokens->_attempt_get_valid_token();

        $data['player'] = (object)[
            'health' => 100,
            'mana' => 100,
            'name' => 'Player',
        ];

        $data['latency'] = 42;

        $this->template('public', $data);
    }
}