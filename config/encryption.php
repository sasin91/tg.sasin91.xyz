<?php

$encryption = [
    'key' => $_ENV['ENCRYPTION_KEY'] ?? 'base64:XjdEsR/ZyAKIKlFh1kYZgqLDLQ1MXeJUetHNND+6uAQ=',
    'cipher' => $_ENV['ENCRYPTION_CIPHER'] ?? 'AES-256-CBC',
    'serialize' => $_ENV['ENCRYPTION_SERIALIZER'] ?? 'json',
];

$GLOBALS['encryption'] = $encryption;


