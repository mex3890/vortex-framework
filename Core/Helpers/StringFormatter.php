<?php

namespace Core\Helpers;

use Core\Abstractions\Enums\PhpExtra;

class StringFormatter
{
    private const NON_SNAKE_CASE = [',', '.', '-', ' '];
    private const NON_CAMEL_CASE = [',', '.', '-', ' ', '_'];

    public static function retrieveSnakeCase(string $string): string
    {
        $array_string = str_split($string);
        $formatted_string = '';
        foreach ($array_string as $key => $character) {
            if (ctype_upper($character) && $key !== 0) {
                $formatted_string .= "_$character";
            } else {
                $formatted_string .= $character;
            }
        }
        return strtolower(str_replace(self::NON_SNAKE_CASE, '_', $formatted_string));
    }

    public static function retrieveCamelCase(string $string): string
    {
        $string = str_replace(self::NON_CAMEL_CASE, ' ', $string);
        $array_string = explode(' ', $string);
        $formatted_string = '';
        foreach ($array_string as $string) {
            $formatted_string .= ucfirst($string);
        }
        return $formatted_string;
    }

    public static function absoluteUpperFistLetter(string $string): string
    {
        return ucfirst(strtolower($string));
    }

    public static function getStringBetween(string $string, string $start, string $end): string
    {
        $string = ' ' . $string;
        $initial = strpos($string, $start);

        if ($initial == 0) {
            return '';
        }

        $initial += strlen($start);
        $len = strpos($string, $end, $initial) - $initial;

        return substr($string, $initial, $len);
    }

    public static function pluralize(string $string): string
    {
        switch ($string) {
            case str_ends_with($string, 'man'):
                $string = substr($string, 0, -2) . 'en';
                break;
            case str_ends_with($string, 'oo') || preg_match("/[aeiou]y$/", $string):
                $string = $string . 's';
                break;
            case str_ends_with($string, 'y'):
                $string = substr($string, 0, -1) . 'ies';
                break;
            case preg_match("/[osxz]$/", $string) ||
                str_ends_with($string, "sh") ||
                str_ends_with($string, "ss") ||
                str_ends_with($string, "ch"):
                $string .= 'es';
                break;
            case str_ends_with($string, 'f'):
                $string = substr($string, 0, -1) . 'ves';
                break;
            case str_ends_with($string, 'fe'):
                $string = substr($string, 0, -2) . 'ves';
                break;
            default:
                $string .= 's';
                break;
        }
        return $string;
    }

    public static function removeWhiteSpace(string $string): string
    {
        return str_replace(PhpExtra::WHITE_SPACE->value, '', $string);
    }

    public static function removeQuotes(string $string): string
    {
        return self::removeDoubleQuotes(self::removeSingleQuotes($string));
    }

    public static function removeSingleQuotes(string $string): string
    {
        return str_replace("'", '', $string);
    }

    public static function removeDoubleQuotes(string $string): string
    {
        return str_replace('"', '', $string);
    }

    public static function escapeSingleQuotes(string $string): string
    {
        return self::escape("'", $string);
    }

    public static function escapeDoubleQuotes(string $string): string
    {
        return self::escape('"', $string);
    }

    public static function escapeQuotes(string $string): string
    {
        return self::escapeDoubleQuotes(self::escapeSingleQuotes($string));
    }

    public static function escape(string $escape_value, string $string): array|string
    {
        return str_replace($escape_value, "/$escape_value", $string);
    }

    public static function str_starts_with(string $string, array|string $substring): bool
    {
        if (is_string($substring)) {
            return str_starts_with($string, $substring);
        }

        foreach ($substring as $value) {
            if (str_starts_with($string, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $array
     * @param bool $is_associative
     * @param string $separator
     * @param bool $with_space
     * @return string
     */
    public static function mountStringByArray(
        array $array,
        bool $is_associative = false,
        string $separator = ',',
        bool $with_space = true
    ): string
    {
        $count = count($array);
        $index = 1;
        $final_string = '';

        if ($with_space) {
            $separator = $separator . PhpExtra::WHITE_SPACE->value;
        }

        if ($is_associative) {
            foreach ($array as $key => $value) {
                if ($value === null) {
                    $value = '';
                }

                if ($index !== $count) {
                    $final_string .= "$key $value$separator";
                    $index++;
                    continue;
                }

                $final_string .= "$key $value";
                $index++;
            }

            return $final_string;
        }

        foreach ($array as $value) {
            if ($index !== $count) {
                $final_string .= "$value$separator";
                $index++;
                continue;
            }

            $final_string .= "$value";
            $index++;
        }

        return $final_string;
    }
}
