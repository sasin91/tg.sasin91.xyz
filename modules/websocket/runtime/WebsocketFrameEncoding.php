<?php

trait WebsocketFrameEncoding
{
    private function encodeWebSocketFrame(string $message): string
    {
        $frameHead = [];
        $payloadLength = strlen($message);

        // Frame header: FIN, Opcode 0x1 (text frame)
        $frameHead[0] = 129;

        if ($payloadLength <= 125) {
            $frameHead[1] = $payloadLength;
        } elseif ($payloadLength <= 65535) {
            $frameHead[1] = 126;
            $frameHead[2] = ($payloadLength >> 8) & 255;
            $frameHead[3] = $payloadLength & 255;
        } else {
            $frameHead[1] = 127;
            for ($i = 0; $i < 8; $i++) {
                $frameHead[9 - $i] = ($payloadLength >> ($i * 8)) & 255;
            }
        }

        // Convert the frame head to a binary string
        $frameHeadStr = "";
        foreach ($frameHead as $b) {
            $frameHeadStr .= chr($b);
        }

        // Return the frame with the payload
        return $frameHeadStr . $message;
    }

    private function decodeWebSocketFrame(string $data): array {
        if (empty($data)) {
            return [];
        }

        // Read the first byte
        $firstByte = ord($data[0]);
        $fin = ($firstByte >> 7) & 0b1; // FIN bit
        $opcode = $firstByte & 0b00001111; // Opcode

        // Read the second byte
        $secondByte = ord($data[1]);
        $masked = ($secondByte >> 7) & 0b1; // Mask bit
        $payloadLength = $secondByte & 0b01111111; // Payload length

        // Determine the payload length
        if ($payloadLength === 126) {
            $payloadLength = unpack('n', substr($data, 2, 2))[1]; // Next 2 bytes for length
            $headerLength = 4; // 2 bytes for extended length
        } elseif ($payloadLength === 127) {
            $payloadLength = unpack('P', substr($data, 2, 8))[1]; // Next 8 bytes for length
            $headerLength = 10; // 8 bytes for extended length
        } else {
            $headerLength = 2; // No extended length
        }

        // Extract the masking key (if present)
        if ($masked) {
            $maskingKey = substr($data, $headerLength, 4);
            $headerLength += 4; // Move past the masking key
        } else {
            $maskingKey = null;
        }

        // Extract the payload data
        $payloadData = substr($data, $headerLength, $payloadLength);

        // Unmask the payload data if it was masked
        if ($masked) {
            for ($i = 0; $i < $payloadLength; ++$i) {
                $payloadData[$i] = chr(ord($payloadData[$i]) ^ ord($maskingKey[$i % 4]));
            }
        }

        return [
            'fin' => $fin,
            'opcode' => $opcode,
            'payload' => $payloadData
        ];
    }
}