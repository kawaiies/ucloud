<?php
namespace app\controller\api;

use app\common\Db;
use app\common\Response;

class Services
{
    public function index(): void
    {
        $category = $_GET['category'] ?? '';
        $sql = 'SELECT * FROM services WHERE status = 1';
        $params = [];
        if ($category !== '') {
            $sql .= ' AND category = ?';
            $params[] = $category;
        }
        $sql .= ' ORDER BY sort_order ASC, id ASC';
        Response::ok(Db::query($sql, $params));
    }

    public function hot(): void
    {
        Response::ok(Db::query('SELECT * FROM services WHERE status = 1 ORDER BY sort_order ASC, id ASC LIMIT 6'));
    }

    public function detail(string $sid): void
    {
        $row = Db::find('SELECT * FROM services WHERE sid = ? AND status = 1', [$sid]);
        $row ? Response::ok($row) : Response::fail('服务不存在', 404);
    }
}
