<?php

class Trongate_comments_model extends Model {
    private string $table = 'trongate_comments';

    public function query(string $sql, ?string $return_type = null): mixed {
        return $this->db->query($sql, $return_type);
    }
}
