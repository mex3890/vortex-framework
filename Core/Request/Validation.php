<?php

namespace Core\Request;

use Core\Database\Schema;
use Core\Helpers\Environment;
use Core\Helpers\StringFormatter;
use DateTime;

class Validation
{
    private const VALIDATION_CLASS = 'Core\Request\Validation';
    private const FILE_REQUIRED_KEYS = ['name', 'full_path', 'type', 'tmp_name', 'size'];

    /**
     * @param array $args
     * @param array $rules
     * @param array $feedback
     * @return bool
     */
    public static function check(array $args, array $rules, array $feedback): bool
    {
        $errors = [];

        unset($_GET['ERROR']);
        unset($_GET['OLD_ATTRIBUTES']);

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

        $_GET['LAST_ROUTE'] = $args['vortex_redirect'];

        unset($args['vortex_redirect']);

        if (!empty($errors)) {
            $_GET['ERROR'] = $errors;
            $_GET['OLD_ATTRIBUTES'] = $args;
            back();
        }

        $_GET['ERROR'] = '';
        $_GET['OLD_ATTRIBUTES'] = '';
        return true;
    }

    /**
     * @param $bool
     * @return bool|string
     */
    private static function bool($bool): bool|string
    {
        if (filter_var($bool, FILTER_VALIDATE_BOOL)) {
            return true;
        }

        return 'bool';
    }

    /**
     * @param $boolean
     * @return bool|string
     */
    private static function boolean($boolean): bool|string
    {
        if (filter_var($boolean, FILTER_VALIDATE_BOOLEAN)) {
            return true;
        }

        return 'boolean';
    }

    /**
     * @param string $date
     * @param string|null $format
     * @return bool|string
     */
    private static function date(string $date, ?string $format = null): bool|string
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

    /**
     * @param string $date
     * @param string|null $format
     * @return bool|string
     */
    private static function date_time(string $date, ?string $format = null): bool|string
    {
        if (strpos($date, 'T')) {
            $date = str_replace('T', ' ', $date);
        }

        if (strlen($date) === 16) {
            $date .= ':00';
        }

        if (!$format) {
            $format = Environment::dateTimeFormat();
        }

        $formatted_date = DateTime::createFromFormat($format, $date);

        if ($formatted_date && $formatted_date->format($format) === $date) {
            return true;
        }

        return 'date_time';
    }

    /**
     * @param string $email
     * @return bool|string
     */
    private static function email(string $email): bool|string
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        return 'email';
    }

    /**
     * @param array $file
     * @param array|null $extensions
     * @param int $limit
     * @return bool|string
     */
    private static function file(array $file, ?array $extensions = null, int $limit = -1): bool|string
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

    /**
     * @param float|int|string $float
     * @param int|null $min
     * @param int|null $max
     * @return bool|string
     */
    private static function float(float|int|string $float, ?int $min = null, ?int $max = null): bool|string
    {
        if (preg_match('/^[-+]?\d*\.?\d+$/', $float)) {
            $float = floatval($float);
        } else {
            return 'float';
        }

        if (!is_null($max)) {
            if ($float > $max) {
                return 'float.max';
            }
        }

        if (!is_null($min)) {
            if ($float < $min) {
                return 'float.min';
            }
        }

        return true;
    }

    /**
     * @param array $image
     * @param array|null $extensions
     * @param int $limit
     * @return bool|string
     */
    private static function image(array $image, ?array $extensions = null, int $limit = -1): bool|string
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

    /**
     * @param int|string $int
     * @param int|null $min
     * @param int|null $max
     * @return bool|string
     */
    private static function int($int, ?int $min = null, ?int $max = null): bool|string
    {
        if (preg_match('/^[-+]?[0-9]+$/', $int)) {
            $int = intval($int);
        } else {
            return 'int';
        }

        if (!is_null($max)) {
            if ($int > $max) {
                return 'int.max';
            }
        }

        if (!is_null($min)) {
            if ($int < $min) {
                return 'int.min';
            }
        }

        return true;
    }

    /**
     * @param string $password
     * @param array|null $requisitions
     * @return bool|string
     */
    private static function password(string $password, ?array $requisitions = null): bool|string
    {
        if (!empty($requisitions)) {
            foreach ($requisitions as $requisition) {
                switch ($requisition) {
                    case 'number':
                        if (!preg_match('/\d/', $password)) {
                            return 'password.number';
                        }
                        break;
                    case 'upper-case':
                        if (!preg_match('/[A-Z]/', $password)) {
                            return 'password.upper-case';
                        }
                        break;
                    case 'lower-case':
                        if (!preg_match('/[a-z]/', $password)) {
                            return 'password.lower-case';
                        }
                        break;
                    case 'special-character':
                        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};\':"\\\|,.<>\/?°]/', $password)) {
                            return 'password.special-character';
                        }
                        break;
                }
            }
        }
        return true;
    }

    /**
     * @param mixed $arg
     * @return bool|string
     */
    private static function required(mixed $arg): bool|string
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
     * @param $string
     * @param int|null $min
     * @param int|null $max
     * @return bool|string
     */
    private static function string($string, ?int $min = null, ?int $max = null): bool|string
    {
        if (!is_string($string)) {
            return 'string';
        }

        if (!is_null($max) && $max > 1) {
            if (strlen($string) > $max) {
                return 'string.max';
            }
        }

        if (!is_null($min) && $min > 1) {
            if (strlen($string) < $min) {
                return 'string.min';
            }
        }

        return true;
    }

    /**
     * @param string $time
     * @param string|null $format
     * @return bool|string
     */
    private static function time(string $time, ?string $format = null): bool|string
    {
        if (strlen($time) === 5) {
            $time .= ':00';
        }

        if (!$format) {
            $format = Environment::timeFormat();
        }

        $formatted_date = DateTime::createFromFormat($format, $time);

        if ($formatted_date && $formatted_date->format($format) === $time) {
            return true;
        }

        return 'date_time';
    }

    /**
     * @param $arg
     * @param $table
     * @param $column
     * @return bool|string
     */
    private static function unique($arg, $table, $column): bool|string
    {
        $search = Schema::select($table, $column)->where($column, $arg)->make();

        if (empty($search)) {
            return true;
        }

        return 'unique';
    }

    /**
     * @param string $url
     * @return bool|string
     */
    private static function url(string $url): bool|string
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        }

        return 'url';
    }

    /**
     * @param array $file
     * @return bool
     */
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
