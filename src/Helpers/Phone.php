<?php

namespace Esyede\BNI\Helpers;

class Phone
{
    public static function toZeroPrefix($number)
    {
        if (static::startsWith($number, '+62')) {
            $number = static::replaceFirst('+62', '0', $number);
        }

        if (static::startsWith($number, '62')) {
            $number = static::replaceFirst('62', '0', $number);
        }

        return $number;
    }

    public static function startsWith($haystack, $needle)
    {
        return ('' !== (string) $needle && 0 === strncmp($haystack, $needle, strlen($needle)));
    }

    public static function replaceFirst($search, $replace, $subject)
    {
        if ('' === $search) {
            return $subject;
        }

        $position = strpos($subject, $search);

        return (false === $position)
            ? $subject
            : substr_replace($subject, $replace, $position, strlen($search));
    }
}