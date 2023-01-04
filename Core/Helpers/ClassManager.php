<?php

namespace Core\Helpers;

class ClassManager
{
    public static function getClassName($model, bool $full_path = true): string
    {
        if ($full_path) {
            if (is_object($model)) {
                return get_class($model);
            }

            return get_class(new $model);
        }

        if (is_object($model)) {
            $class = explode('\\', get_class($model));
            $count = count($class);

            return $class[$count - 1];
        }

        $class = explode('\\', get_class(new $model));
        $count = count($class);
        return $class[$count - 1];
    }

    public static function callStaticFunction(string $class, string $method): void
    {
        call_user_func("$class::$method");
    }
}
