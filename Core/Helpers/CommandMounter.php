<?php

namespace Core\Helpers;

class CommandMounter
{
    public static function mountMethodCallerCommand(string $class_path, string $static_function): bool|string|null
    {
        $class = explode('.', $class_path)[0];
        return shell_exec('php -r ' . '"require ' . "'$class_path'; " . "$class::$static_function()" . ';"');
    }

    public static function retrieveLoadPoints(int $non_point_string): string
    {
        return str_repeat('.', exec('tput cols') - $non_point_string);
    }
}
