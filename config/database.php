<?php
return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'type' => 'mysql',
            'hostname' => env('MYSQL_HOST', '127.0.0.1'),
            'hostport' => env('MYSQL_PORT', '3306'),
            'database' => env('MYSQL_DATABASE', 'aijiu'),
            'username' => env('MYSQL_USERNAME', 'root'),
            'password' => env('MYSQL_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'prefix' => '',
        ],
    ],
];
