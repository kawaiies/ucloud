<?php
namespace app\controller\api;

use app\common\Db;
use app\common\Response;

class Therapists
{
    public function index(): void
    {
        Response::ok(Db::query('SELECT * FROM therapists WHERE status = 1 ORDER BY sort_order ASC, id ASC'));
    }

    public function detail(string $id): void
    {
        $row = Db::find('SELECT * FROM therapists WHERE id = ? AND status = 1', [(int) $id]);
        $row ? Response::ok($row) : Response::fail('技师不存在', 404);
    }
}
