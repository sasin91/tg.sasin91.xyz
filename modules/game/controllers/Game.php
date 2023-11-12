<?php
class Game extends Trongate {

    function index () {
        $data['view_module'] = 'game';
        $this->view('display', $data);
    }

}