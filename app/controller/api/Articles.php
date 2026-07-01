<?php
namespace app\controller\api;

use app\common\Db;
use app\common\Response;

class Articles
{
    public function index(): void
    {
        Response::ok(Db::query('SELECT * FROM articles WHERE status = 1 ORDER BY publish_date DESC, id DESC'));
    }

    public function detail(string $aid): void
    {
        $row = Db::find('SELECT * FROM articles WHERE aid = ? AND status = 1', [$aid]);
        if (!$row) {
            Response::fail('文章不存在', 404);
        }
        Db::exec('UPDATE articles SET views = views + 1 WHERE id = ?', [$row['id']]);
        Response::ok($row);
    }
}
