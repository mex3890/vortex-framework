<?php

namespace Core\Request;

class Validation
{
    private const VALIDATION_CLASS = 'Core\Request\Validation';

    public static function check(array $args, array $rules, array $feedback): bool
    {
        $errors = [];

        unset($_SESSION['ERROR']);
        unset($_SESSION['OLD_ATTRIBUTES']);

        foreach ($args as $key => $value) {

            if (key_exists($key, $rules)) {

                $key_validations = $rules[$key];

                foreach ($key_validations as $validation) {
                    if (!is_array($validation)) {
                        $log = call_user_func(self::VALIDATION_CLASS . "::$validation", $value);
                    } else {
                        $log = call_user_func(self::VALIDATION_CLASS . "::$validation[0]", $value, $validation[1], $validation[2]);
                    }

                    if ($log !== true) {
                        $errors[$key][] = $feedback[$key][$log] ?? "$validation Error!";
                    }
                }
            }
        }

        if (!empty($errors)) {
            $_SESSION['ERROR'] = $errors;
            $_SESSION['OLD_ATTRIBUTES'] = $args;
            back();
        }

        $_SESSION['ERROR'] = '';
        $_SESSION['OLD_ATTRIBUTES'] = '';
        return true;
    }

    private static function email($email): bool|string
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        return 'email';
    }

    private static function bool($bool): bool|string
    {
        if (filter_var($bool, FILTER_VALIDATE_BOOL)) {
            return true;
        }

        return 'bool';
    }

    private static function boolean($boolean): bool|string
    {
        if (filter_var($boolean, FILTER_VALIDATE_BOOLEAN)) {
            return true;
        }

        return 'boolean';
    }

    private static function float($float, $min = null, $max = null): bool|string
    {
        if(!is_null($max) && !is_null($min) && is_float($float)) {
            if($float < $min) {
                return 'float.min';
            } elseif ($float > $max) {
                return 'float.max';
            }
        }

        if (filter_var($float, FILTER_VALIDATE_FLOAT)) {
            return true;
        }

        return 'float';
    }

    private static function int($int, $min = null, $max = null): bool|string
    {
        if(!is_null($max) && !is_null($min) && is_int($int)) {
            if($int < $min) {
                return 'int.min';
            } elseif ($int > $max) {
                return 'int.max';
            }
        }

        if (filter_var($int, FILTER_VALIDATE_INT)) {
            return true;
        }

        return 'int';
    }

    private static function url($url): bool|string
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        }

        return 'url';
    }

    private static function string($string, $min = null, $max = null): bool|string
    {
        if(!is_null($max) && !is_null($min) && is_string($string)) {
            $lenght = strlen($string);
            if($lenght < $min) {
                return 'string.min';
            } elseif ($lenght > $max) {
                return 'string.max';
            }
        }

        if (is_string($string)) {
            return true;
        }

        return 'string';
    }

    private static function file($file): bool|string
    {
        if(is_file($file)) {
            return true;
        }

        return 'file';
    }

    private static function required($arg): bool|string
    {
        if (isset($arg) && $arg !== '') {
            return true;
        }

        return 'required';
    }
}
