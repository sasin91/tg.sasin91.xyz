<?php

require_once __DIR__ . '/Tenancy_model.php';

class Tenancy extends Trongate
{
    private Tenancy_model $tenancy_model;

    public function __construct(?string $module_name = null)
    {
        parent::__construct($module_name);
        $this->tenancy_model = new Tenancy_model('tenancy');
    }

    /**
     * Bootstrap the tenancy context for the current request.
     *
     * @return void
     */
    public function bootstrap(): void
    {
        if (memo('tenant')) {
            return;
        }

        $host_header = $_SERVER['HTTP_HOST'] ?? '';
        $host = $this->normalise_host($host_header);

        if ($host === '') {
            $fallback_host = parse_url(BASE_URL, PHP_URL_HOST) ?: '';
            $host = $this->normalise_host($fallback_host);

            if ($host === '') {
                throw new RuntimeException('Unable to resolve tenant without a valid host.');
            }
        }

        $tenant = $this->resolve_tenant($host);

        memo('tenant', $tenant);

        $this->configure_tenant_database($tenant);
    }

    /**
     * Normalise a host/domain string for comparison.
     *
     * @param string $host
     * @return string
     */
    private function normalise_host(string $host): string
    {
        $host = strtolower(trim($host));

        if ($host === '') {
            return '';
        }

        $host = explode('/', $host)[0];

        if (str_contains($host, ':')) {
            $host = explode(':', $host)[0];
        }

        if (str_starts_with($host, 'www.')) {
            $host = substr($host, 4);
        }

        return $host;
    }

    /**
     * Resolve the tenant for the given domain, with optional fallbacks.
     *
     * @param string $domain
     * @return array{id:int,domain:string,database_config:array}
     */
    private function resolve_tenant(string $domain): array
    {
        $tenant = $this->tenancy_model->find_by_domain($domain);

        if (!$tenant) {
            throw new RuntimeException("Tenant not found for host '{$domain}'.");
        }

        return $tenant;
    }

    /**
     * Update the default database connection to point at the tenant schema.
     *
     * @param array{id:int,domain:string,database_config:array} $tenant
     * @return void
     */
    private function configure_tenant_database(array $tenant): void
    {
        if (!isset($GLOBALS['databases']['default'])) {
            throw new RuntimeException('Default database configuration is missing.');
        }

        $config = $GLOBALS['databases']['default'];

        $database_config = $tenant['database_config'] ?? [];

        if (isset($database_config['host'])) {
            $config['host'] = $database_config['host'];
        }

        if (isset($database_config['port'])) {
            $config['port'] = $database_config['port'];
        }

        if (isset($database_config['user'])) {
            $config['user'] = $database_config['user'];
        }

        if (isset($database_config['password'])) {
            $config['password'] = $database_config['password'];
        }

        if (isset($database_config['database'])) {
            $config['database'] = $database_config['database'];
        }

        $GLOBALS['databases']['default'] = $config;
    }
}

