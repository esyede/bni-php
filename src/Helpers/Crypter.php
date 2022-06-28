<?php

namespace Esyede\BNI\Helpers;

class Crypter
{
    const TIME_DIFF_LIMIT = 480;

    public static function encrypt(array $jsonData, $clientId, $secret)
    {
        return static::doubleEncrypt(strrev(time()) . '.' . json_encode($jsonData), $clientId, $secret);
    }

    public static function decrypt($hash, $clientId, $secret)
    {
        $parsed = static::doubleDecrypt($hash, $clientId, $secret);
        list($timestamp, $jsonData) = array_pad(explode('.', $parsed, 2), 2, null);

        if (static::timestampDiff(strrev($timestamp)) === true) {
            return json_decode($jsonData, true);
        }

        return null;
    }

    private static function timestampDiff($timestamp)
    {
        return abs($timestamp - time()) <= static::TIME_DIFF_LIMIT;
    }

    private static function doubleEncrypt($string, $clientId, $secret)
    {
        $result = '';
        $result = static::enc($string, $clientId);
        $result = static::enc($result, $secret);

        return strtr(rtrim(base64_encode($result), '='), '+/', '-_');
    }

    private static function enc($string, $key)
    {
        $result = '';
        $lengthStr = strlen($string);
        $lengthKey = strlen($key);

        for ($i = 0; $i < $lengthStr; $i++) {
            $char = substr($string, $i, 1);
            $keyChar = substr($key, ($i % $lengthKey) - 1, 1);
            $char = chr((ord($char) + ord($keyChar)) % 128);
            $result .= $char;
        }

        return $result;
    }

    private static function doubleDecrypt($string, $clientId, $secret)
    {
        $result = str_pad($string, ceil(strlen($string) / 4) * 4, '=', STR_PAD_RIGHT);
        $result = base64_decode(strtr($result, '-_', '+/'));
        $result = static::dec($result, $clientId);
        $result = static::dec($result, $secret);

        return $result;
    }

    private static function dec($string, $key)
    {
        $result = '';
        $lengthStr = strlen($string);
        $lengthKey = strlen($key);

        for ($i = 0; $i < $lengthStr; $i++) {
            $char = substr($string, $i, 1);
            $keyChar = substr($key, ($i % $lengthKey) - 1, 1);
            $char = chr(((ord($char) - ord($keyChar)) + 256) % 128);
            $result .= $char;
        }

        return $result;
    }
}