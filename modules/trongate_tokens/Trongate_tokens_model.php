<?php

class Trongate_tokens_model extends Model {
    private string $table = 'trongate_tokens';

    public function query_bind(string $sql, array $params, ?string $return_type = null): mixed {
        return $this->db->query_bind($sql, $params, $return_type);
    }

    public function insert(array $data, ?string $target_table = null): ?int {
        $target_table ??= $this->table;
        return $this->db->insert($data, $target_table);
    }

    public function update(int $update_id, array $data, ?string $target_table = null): bool {
        $target_table ??= $this->table;
        return $this->db->update($update_id, $data, $target_table);
    }
}
