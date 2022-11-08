<?php

namespace Core\Helpers;

class Hash
{
    private const HASH_ALGORITHM = 'hash_algorithm';
    private const HASH_ALGORITHM_COST = 'hash_algorithm';
    private const DEFAULT_HASH_ALGORITHM = 'rasmuslerdorf';
    private const DEFAULT_HASH_ALGORITHM_COST = 12;

    public static function hashPassword(string $password): string
    {
        return password_hash(
            $password,
            $_ENV[self::HASH_ALGORITHM] ?? self::DEFAULT_HASH_ALGORITHM,
            ['cost' => $_ENV[self::HASH_ALGORITHM_COST] ?? self::DEFAULT_HASH_ALGORITHM_COST]);
    }

    public static function verifyPassword(string $password, string $hash_password): bool
    {
        return password_verify($password, $hash_password);
    }
}