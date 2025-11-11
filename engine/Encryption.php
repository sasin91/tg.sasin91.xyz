<?php

class Encryption
{
    private string $cipher;
    private bool $serialize;
    private string $key;
    private string $mac_key;

    public function __construct(?array $config = null)
    {
        $config ??= $GLOBALS['encryption'] ?? [];

        $cipher = $config['cipher'] ?? 'AES-256-CBC';
        $key = $config['key'] ?? '';
        $serialize = $config['serialize'] ?? 'json';

        if ($key === '') {
            throw new RuntimeException('Encryption key is not defined. Check config/encryption.php.');
        }

        if (str_starts_with($key, 'base64:')) {
            $decoded = base64_decode(substr($key, 7), true);

            if ($decoded === false) {
                throw new RuntimeException('Invalid base64 encoding for encryption key.');
            }

            $key = $decoded;
        }

        if (openssl_cipher_iv_length($cipher) === false) {
            throw new RuntimeException("Unsupported cipher: {$cipher}");
        }

        $this->cipher = $cipher;
        $this->serialize = strtolower((string) $serialize) === 'json';
        $this->key = $key;
        $this->mac_key = hash('sha256', $key, true);
    }

    public function encrypt(mixed $value): string
    {
        $iv_length = openssl_cipher_iv_length($this->cipher);
        $iv = random_bytes($iv_length);

        if ($this->serialize) {
            $payload = json_encode($value, JSON_UNESCAPED_UNICODE);

            if ($payload === false) {
                throw new RuntimeException('Unable to JSON encode value for encryption.');
            }
        } else {
            $payload = (string) $value;
        }

        $encrypted = openssl_encrypt(
            $payload,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );

        if ($encrypted === false) {
            throw new RuntimeException('Unable to encrypt value.');
        }

        $mac = hash_hmac('sha256', $iv . $encrypted, $this->mac_key, true);

        $json = json_encode([
            'iv' => base64_encode($iv),
            'value' => base64_encode($encrypted),
            'mac' => base64_encode($mac),
            'serialized' => $this->serialize,
        ], JSON_UNESCAPED_SLASHES);

        if ($json === false) {
            throw new RuntimeException('Unable to encode encrypted payload.');
        }

        return base64_encode($json);
    }

    public function decrypt(?string $value): mixed
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        $decoded = base64_decode($value, true);

        if ($decoded === false) {
            throw new RuntimeException('Encrypted payload is not valid base64.');
        }

        $payload = json_decode($decoded, true);

        if (!is_array($payload) || !isset($payload['iv'], $payload['value'], $payload['mac'])) {
            throw new RuntimeException('Encrypted payload structure is invalid.');
        }

        $iv = base64_decode($payload['iv'], true);
        $encrypted = base64_decode($payload['value'], true);
        $mac = base64_decode($payload['mac'], true);

        if ($iv === false || $encrypted === false || $mac === false) {
            throw new RuntimeException('Encrypted payload components are invalid base64.');
        }

        $expected_mac = hash_hmac('sha256', $iv . $encrypted, $this->mac_key, true);

        if (!hash_equals($expected_mac, $mac)) {
            throw new RuntimeException('Encrypted payload MAC is invalid.');
        }

        $decrypted = openssl_decrypt(
            $encrypted,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv
        );

        if ($decrypted === false) {
            throw new RuntimeException('Unable to decrypt payload.');
        }

        $serialized = $payload['serialized'] ?? $this->serialize;

        if ($serialized) {
            $result = json_decode($decrypted, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException('Unable to decode decrypted JSON payload.');
            }

            return $result;
        }

        return $decrypted;
    }
}


