<?php

namespace Core\Helpers;

class ArrayFormatter
{
    public static function castingArrayToString(array $array): string
    {
        $string = "[" . PHP_EOL;

        foreach ($array as $key => $value) {
            $string .= "   $key: $value," . PHP_EOL;
        }

        return $string .= ']';
    }

    public static function isAssoc(array $array): bool
    {
        if ([] === $array) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }
}
