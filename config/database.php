<?php
//Database settings
define('HOST', $_ENV['DATABASE_HOST'] ?? 'localhost');
define('PORT', $_ENV['DATABASE_PORT'] ?? '3306');
define('USER', $_ENV['DATABASE_USER'] ?? 'root');
define('PASSWORD', $_ENV['DATABASE_PASSWORD'] ?? 'jonas2904');
define('DATABASE', $_ENV['DATABASE_NAME'] ?? 'sasin91');

define('REDIS_HOST', $_ENV['REDIS_HOST'] ?? '127.0.0.1');
define('REDIS_PORT', $_ENV['REDIS_PORT'] ?? '6379');
