<?php
// Database connection credentials
// The 'default' group is used when you call $this->db
// Additional groups can be accessed via $this->groupname in model files (e.g., $this->analytics)
// Note: Alternative database groups can ONLY be accessed from model files, not controllers
$databases = [
    'default' => [
        'host' => $_ENV['DATABASE_HOST'] ?? '127.0.0.1',
        'port' => $_ENV['DATABASE_PORT'] ?? '3306',
        'user' => $_ENV['DATABASE_USER'] ?? 'root',
        'password' => $_ENV['DATABASE_PASSWORD'] ?? '',
        'database' => $_ENV['DATABASE_NAME'] ?? 'sasin91'
    ],
];

// Example: Multiple database configuration
// Uncomment and modify the section below to add additional database connections
/*
$databases = [
    'default' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'user' => 'root',
        'password' => '',
        'database' => 'default_db'
    ],
    'analytics' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'user' => 'analytics_user',
        'password' => 'analytics_pass',
        'database' => 'analytics_db'
    ],
    'legacy' => [
        'host' => '192.168.1.50',
        'port' => '3306',
        'user' => 'legacy_user',
        'password' => 'legacy_pass',
        'database' => 'old_system'
    ]
];
*/
define('REDIS_HOST', $_ENV['REDIS_HOST'] ?? '127.0.0.1');
define('REDIS_PORT', $_ENV['REDIS_PORT'] ?? '6379');
