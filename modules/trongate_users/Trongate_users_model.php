<?php

class Trongate_users_model extends Model {
    private string $table = 'trongate_users';

    public function insert(array $data, ?string $target_table = null): ?int {
        $target_table ??= $this->table;
        return $this->db->insert($data, $target_table);
    }
}
