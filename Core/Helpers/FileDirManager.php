<?php

namespace Core\Helpers;

use Core\Exceptions\OnWrite;

class FileDirManager
{
    public static function retrieveFilesByDirectory(string $path): bool|array
    {
        return array_diff(scandir($path), array('.', '..'));
    }

    public static function fileExistInDirectory(string $file_name, string $file_path): bool
    {
        $files = [];
        foreach (FileDirManager::retrieveFilesByDirectory($file_path) as $file) {
            $files[] = $file;
        };

        return in_array($file_name, $files);
    }

    public static function createFileByTemplate(string $file_name, string $final_path, string $content_path, array $changes = null): void
    {
        $new_file_content = file_get_contents(realpath($content_path));
        foreach ($changes as $key => $change) {
            $new_file_content = str_replace($key, $change, $new_file_content);
            $fp = fopen(realpath($final_path) . "/$file_name", 'w');
            fwrite($fp, $new_file_content);
            fclose($fp);
        }
    }

    public static function createFileByContent(string $file_name, string $final_path, string $file_content): void
    {
        $fp = fopen(realpath($final_path) . "/$file_name", 'w');
        fwrite($fp, $file_content);
        fclose($fp);
    }
}
