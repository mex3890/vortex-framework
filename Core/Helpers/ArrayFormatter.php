<?php

namespace Core\Helpers;

class ArrayFormatter
{
    public static function castingArrayToString(array $array)
    {
        $string = "[" . PHP_EOL;

        foreach ($array as $key => $value) {
            $string .= "   $key: $value," . PHP_EOL;
        }

        return $string .= ']';
    }

    function isAssoc(array $array)
    {
        if (array() === $array) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }
}
