<?php

class Websocket extends Trongate
{
    public function auth()
    {
        $trongateToken = $_SERVER['HTTP_TOKEN'];

        $this->module('trongate_tokens');
        $this->trongate_tokens->_get_user_id($trongateToken);
    }
}