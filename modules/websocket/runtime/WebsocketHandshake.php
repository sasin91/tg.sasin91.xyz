<?php

trait WebsocketHandshake
{
    protected function handleWebSocketHandshake($clientSocket, $requestHeaders): void
    {
        // Extract the Sec-WebSocket-Key from the request headers
        if (preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $requestHeaders, $matches)) {
            $secWebSocketKey = trim($matches[1]);

            // Create the Sec-WebSocket-Accept response key
            $secWebSocketAccept = base64_encode(pack('H*', sha1($secWebSocketKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));

            // Prepare WebSocket handshake response
            $response = "HTTP/1.1 101 Switching Protocols\r\n";
            $response .= "Upgrade: websocket\r\n";
            $response .= "Connection: Upgrade\r\n";
            $response .= "Sec-WebSocket-Accept: $secWebSocketAccept\r\n\r\n";

            // Send handshake response
            fwrite($clientSocket, $response);
        }
    }

    protected function parseHeaders(string $header_string): array
    {
        $headers = [];
        $lines = explode("\r\n", $header_string);

        foreach ($lines as $line) {
            if (str_contains($line, ": ")) {
                list($key, $value) = explode(": ", $line, 2);
                $headers[trim($key)] = trim($value);
            }
        }

        return $headers;
    }
}