<?php
namespace app\controller;

use app\common\Config;
use app\common\Db;
use app\common\Response;

class Health
{
    public function db(): void
    {
        try {
            Db::find('SELECT 1 AS ok');
            Response::ok([
                'service' => 'thinkphp-nginx-oe3u',
                'dbConnected' => true,
                'dbName' => Config::db()['database'],
                'timestamp' => time(),
            ]);
        } catch (\Throwable $e) {
            Response::json([
                'success' => false,
                'service' => 'thinkphp-nginx-oe3u',
                'dbConnected' => false,
                'message' => $e->getMessage(),
                'timestamp' => time(),
            ], 500);
        }
    }
}
