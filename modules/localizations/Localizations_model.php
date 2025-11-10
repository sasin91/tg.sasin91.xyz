<?php

class Localizations_model extends Model {
    private string $table = 'localizations';

    public function fetch_translations(string $module, string $locale): array {
        $sql = 'SELECT `key`, `value` FROM `localizations` WHERE `module` = :module AND `locale` = :locale';
        $params = [
            ':module' => $module,
            ':locale' => $locale,
        ];

        $rows = $this->db->query_bind($sql, $params, 'assoc');

        $translations = [];
        foreach ($rows as $row) {
            $translations[$row['key']] = $row['value'];
        }

        return $translations;
    }

    public function get(?string $order_by = null, ?string $target_table = null, ?int $limit = null, int $offset = 0): array {
        $target_table ??= $this->table;
        return $this->db->get($order_by, $target_table, $limit, $offset);
    }

    public function get_where(int $id, ?string $target_table = null): object|false {
        $target_table ??= $this->table;
        return $this->db->get_where($id, $target_table);
    }

    public function insert(array $data, ?string $target_table = null): ?int {
        $target_table ??= $this->table;
        return $this->db->insert($data, $target_table);
    }

    public function update(int $update_id, array $data, ?string $target_table = null): bool {
        $target_table ??= $this->table;
        return $this->db->update($update_id, $data, $target_table);
    }

    public function delete(int $update_id, ?string $target_table = null): bool {
        $target_table ??= $this->table;
        return $this->db->delete($update_id, $target_table);
    }

    public function query_bind(string $sql, array $params, ?string $return_type = null): mixed {
        return $this->db->query_bind($sql, $params, $return_type);
    }
}
