<?php
namespace app\common;

final class Config
{
    public static function env(string $key, $default = null)
    {
        $value = $_ENV[$key] ?? getenv($key);
        return ($value === false || $value === null || $value === '') ? $default : $value;
    }

    public static function db(): array
    {
        return [
            'host' => self::env('MYSQL_HOST', '127.0.0.1'),
            'port' => (int) self::env('MYSQL_PORT', '3306'),
            'database' => self::env('MYSQL_DATABASE', 'aijiu'),
            'username' => self::env('MYSQL_USERNAME', 'root'),
            'password' => self::env('MYSQL_PASSWORD', ''),
            'charset' => 'utf8mb4',
        ];
    }

    public static function wxAppId(): string
    {
        return (string) self::env('WX_APPID', '');
    }

    public static function wxSecret(): string
    {
        return (string) self::env('WX_SECRET', '');
    }
}
