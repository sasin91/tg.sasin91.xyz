<?php
//The main config file
define('WEBSOCKET_URL', $_ENV['WEBSOCKET_URL'] ?? 'wss://sasin91.test/ws');
define('BASE_URL', $_ENV['BASE_URL'] ?? 'https://sasin91.test/');
define('ENV', 'dev');

// Mux.com API configuration
define('MUX_TOKEN_ID', $_ENV['MUX_TOKEN_ID'] ?? '');
define('MUX_TOKEN_SECRET', $_ENV['MUX_TOKEN_SECRET'] ?? '');
define('DEFAULT_MODULE', 'welcome');
define('DEFAULT_CONTROLLER', 'Welcome');
define('DEFAULT_METHOD', 'index');
define('MODULE_ASSETS_TRIGGER', '_module');
define('INTERCEPT_404', 'trongate_pages/attempt_display');
