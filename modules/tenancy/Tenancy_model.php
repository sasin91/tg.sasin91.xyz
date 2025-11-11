<?php

class Tenancy_model extends Model
{
    /**
     * Find a tenant record by domain.
     *
     * @param string $domain
     * @return array{id:int,domain:string,database_config:array}|null
     */
    public function find_by_domain(string $domain): ?array
    {
        $domain = trim(strtolower($domain));

        if ($domain === '') {
            return null;
        }

        $sql = 'SELECT id, domain, database_config FROM tenants WHERE domain = :domain LIMIT 1';

        $rows = $this->db->query_bind($sql, ['domain' => $domain], 'object');

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate_tenant($rows[0]);
    }

    /**
     * Find a tenant record by id.
     *
     * @param int $id
     * @return array{id:int,domain:string,database_config:array}|null
     */
    public function find_by_id(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $sql = 'SELECT id, domain, database_config FROM tenants WHERE id = :id LIMIT 1';

        $rows = $this->db->query_bind($sql, ['id' => $id], 'object');

        if (empty($rows)) {
            return null;
        }

        return $this->hydrate_tenant($rows[0]);
    }

    /**
     * Convert a database row into a usable tenant array.
     *
     * @param object $row
     * @return array{id:int,domain:string,database_config:array}
     */
    private function hydrate_tenant(object $row): array
    {
        $database_config = decrypt($row->database_config ?? null) ?? [];

        if (!is_array($database_config)) {
            throw new RuntimeException('Decrypted tenant database configuration is invalid.');
        }

        if (!isset($database_config['database'])) {
            $database_config['database'] = 'tenant_' . (int) $row->id;
        }

        return [
            'id' => (int) $row->id,
            'domain' => strtolower($row->domain),
            'database_config' => $database_config,
        ];
    }
}


