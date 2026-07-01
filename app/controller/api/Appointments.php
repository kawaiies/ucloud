<?php
namespace app\controller\api;

use app\common\Db;
use app\common\Response;

class Appointments
{
    public function index(): void
    {
        $userId = (int) ($_GET['user_id'] ?? 0);
        if (!$userId) {
            Response::fail('缺少user_id', 400);
        }

        Response::ok(Db::query(
            'SELECT a.*, s.name AS service_name, s.image AS service_image, t.name AS therapist_name, t.avatar AS therapist_avatar
             FROM appointments a
             LEFT JOIN services s ON a.service_id = s.id
             LEFT JOIN therapists t ON a.therapist_id = t.id
             WHERE a.user_id = ? ORDER BY a.created_at DESC',
            [$userId]
        ));
    }

    public function create(): void
    {
        $body = json_decode(file_get_contents('php://input') ?: '[]', true) ?: [];
        foreach (['user_id', 'service_id', 'appoint_date', 'time_slot'] as $required) {
            if (empty($body[$required])) {
                Response::fail('缺少' . $required, 400);
            }
        }

        $service = Db::find('SELECT price, duration FROM services WHERE id = ? AND status = 1', [(int) $body['service_id']]);
        if (!$service) {
            Response::fail('服务不存在', 404);
        }

        $orderNo = 'AP' . date('YmdHis') . random_int(1000, 9999);
        Db::exec(
            'INSERT INTO appointments (order_no, user_id, service_id, therapist_id, appoint_date, time_slot, duration, price, notes, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, "pending")',
            [
                $orderNo,
                (int) $body['user_id'],
                (int) $body['service_id'],
                !empty($body['therapist_id']) ? (int) $body['therapist_id'] : null,
                $body['appoint_date'],
                $body['time_slot'],
                (int) ($service['duration'] ?? 60),
                (float) ($service['price'] ?? 0),
                (string) ($body['notes'] ?? ''),
            ]
        );

        Response::ok(['order_no' => $orderNo]);
    }

    public function cancel(string $id): void
    {
        $affected = Db::exec('UPDATE appointments SET status = "cancelled" WHERE id = ? AND status IN ("pending", "confirmed")', [(int) $id]);
        $affected ? Response::ok(['cancelled' => true]) : Response::fail('取消失败', 400);
    }
}
