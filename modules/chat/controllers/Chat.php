<?php

class Chat extends Trongate {
  public function _on_websocket_message(array $json, array $state) {
     $data = $json['data'];
     $userId = $state['user_id'];

     var_dump($data, $state);

     return $data;
  } 
}
