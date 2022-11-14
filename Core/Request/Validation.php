<?php

namespace Core\Request;

use Core\Helpers\StringFormatter;
use DateTime;

class Validation
{
    private const VALIDATION_CLASS = 'Core\Request\Validation';
    private const FILE_REQUIRED_KEYS = ['name', 'full_path', 'type', 'tmp_name', 'size'];

    public static function check(array $args, array $rules, array $feedback): bool
    {
        $errors = [];

        unset($_SESSION['ERROR']);
        unset($_SESSION['OLD_ATTRIBUTES']);

        if (!empty($_FILES)) {
            $args = array_merge($args, $_FILES);
        }

        foreach ($args as $key => $value) {

            $has_required_error = false;

            if (key_exists($key, $rules)) {

                $key_validations = $rules[$key];

                foreach ($key_validations as $validation) {
                    if (!is_array($validation)) {
                        $log = call_user_func(self::VALIDATION_CLASS . "::$validation", $value);
                    } elseif (count($validation) === 2) {
                        $log = call_user_func(self::VALIDATION_CLASS . "::$validation[0]", $value, $validation[1]);
                        if ($key === 'image') {
                            $args[$key] = $value['name'];
                        }
                    } else {
                        $log = call_user_func(self::VALIDATION_CLASS . "::$validation[0]", $value, $validation[1], $validation[2]);
                    }

                    if ($log !== true) {
                        if ($log === 'required') {
                            $has_required_error = $feedback[$key][$log] ?? 'Invalid ' . StringFormatter::absoluteUpperFistLetter($key);
                        }
                        $errors[$key][] = $feedback[$key][$log] ?? 'Invalid ' . StringFormatter::absoluteUpperFistLetter($key);
                    }

                    if (key_exists($key, $errors)) {
                        $errors[$key] = array_unique($errors[$key]);
                    }
                }

                // If you have required error set only this error
                if ($has_required_error) {
                    $errors[$key] = $has_required_error;
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
        if (!is_null($max) && !is_null($min) && is_float($float)) {
            if ($float < $min) {
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
        if (!is_null($max) && !is_null($min) && is_int($int)) {
            if ($int < $min) {
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
        if (!is_null($max) && !is_null($min) && is_string($string)) {
            $length = strlen($string);
            if ($length < $min) {
                return 'string.min';
            } elseif ($length > $max) {
                return 'string.max';
            }
        }

        if (is_string($string)) {
            return true;
        }

        return 'string';
    }

    /**
     * @param array $file
     * @param array|null $extensions
     * @param int $limit
     * @return bool|string
     */
    private static function file(array $file, array $extensions = null, int $limit = -1): bool|string
    {
        if (self::isPostFile($file)) {
            if ($limit === -1 || $file['size'] <= $limit * 1024) {
                $type = explode('/', $file['type']);
                if (!$extensions) {
                    if ($type[0] === 'application') {
                        return true;
                    } else {
                        return 'file.extension';
                    }
                } else {
                    if (in_array($type[1], $extensions) && $type[0] === 'application') {
                        return true;
                    } else {
                        return 'file.extension';
                    }
                }
            } else {
                return 'file.limit';
            }
        }

        return 'file';
    }

    private static function required($arg): bool|string
    {
        if (is_array($arg)) {
            foreach ($arg as $value) {
                if ($value === '') {
                    return 'required';
                }
            }
        }

        if (isset($arg) && $arg !== '') {
            return true;
        }

        return 'required';
    }

    /**
     * @param array $image
     * @param array|null $extensions
     * @param int $limit
     * @return bool|string
     */
    private static function image(array $image, array $extensions = null, int $limit = -1): bool|string
    {
        if (self::isPostFile($image)) {
            if ($limit === -1 || $image['size'] <= $limit * 1024) {
                $type = explode('/', $image['type']);
                if (!$extensions) {
                    if ($type[0] === 'image') {
                        return true;
                    } else {
                        return 'image.extension';
                    }
                } else {
                    if (in_array($type[1], $extensions) && $type[0] === 'image') {
                        return true;
                    } else {
                        return 'image.extension';
                    }
                }
            } else {
                return 'image.limit';
            }
        }

        return 'image';
    }

    private static function date(string $date, string $format = null): bool|string
    {
        if (!$format) {
            $format = $_ENV['DATE_FORMAT'];
        }

        $formatted_date = DateTime::createFromFormat($format, $date);

        if ($formatted_date && $formatted_date->format($format) === $date) {
            return true;
        }

        return 'date';
    }

    private static function isPostFile(array $file): bool
    {
        $is_file = true;
        foreach (self::FILE_REQUIRED_KEYS as $key) {
            if (!key_exists($key, $file)) {
                $is_file = false;
            }
        }

        return $is_file && $file['error'] === 0;
    }
}
