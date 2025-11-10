<?php

class Trongate_user_levels_model extends Model {
    private string $table = 'trongate_user_levels';

    public function query_bind(string $sql, array $params, ?string $return_type = null): mixed {
        return $this->db->query_bind($sql, $params, $return_type);
    }
}
