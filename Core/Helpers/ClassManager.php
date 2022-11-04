<?php

namespace Core\Helpers;

class ClassManager
{
    public static function getClassName($model): string
    {
        if (is_object($model)) {
            return get_class($model);
        }

        return get_class(new $model);
    }

    public static function callStaticFunction(string $class, string $method): void
    {
        call_user_func("$class::$method");
    }
}
