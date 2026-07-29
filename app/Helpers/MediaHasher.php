<?php

namespace App\Helpers;

class MediaHasher
{
    private static int $salt = 0x7E3F;
    private static int $multiplier = 97;
    private static int $offset = 104729;

    /**
     * Encode media ID into a clean, short encrypted token.
     */
    public static function encode(int $id): string
    {
        $obfuscated = ($id ^ self::$salt) * self::$multiplier + self::$offset;
        return rtrim(strtr(base64_encode((string)$obfuscated), '+/', '-_'), '=');
    }

    /**
     * Decode short token back to original media ID.
     */
    public static function decode(string $token): ?int
    {
        $b64 = strtr($token, '-_', '+/');
        $pad = strlen($b64) % 4;
        if ($pad) {
            $b64 .= str_repeat('=', 4 - $pad);
        }
        $decoded = base64_decode($b64);
        if (!is_numeric($decoded)) {
            return null;
        }
        $num = (int)$decoded;
        $id = (($num - self::$offset) / self::$multiplier) ^ self::$salt;
        return (is_int($id) && $id > 0) ? (int)$id : null;
    }
}
