<?php
namespace app\controller\api;

use app\common\Db;
use app\common\Response;

class ServiceCards
{
    public function index(): void
    {
        $userId = (int) ($_GET['user_id'] ?? 0);
        if (!$userId) {
            Response::fail('缺少user_id', 400);
        }

        Response::ok(Db::query(
            'SELECT sc.*, s.name AS service_name, s.image AS service_image
             FROM service_cards sc
             LEFT JOIN services s ON sc.service_id = s.id
             WHERE sc.user_id = ?
             ORDER BY sc.created_at DESC',
            [$userId]
        ));
    }
}
