<?php
namespace app\controller\api;

use app\common\Db;
use app\common\Response;

class Pay
{
    public function list(): void
    {
        $openid = (string) ($_GET['openid'] ?? '');
        if ($openid === '') {
            Response::fail('缺少openid', 400);
        }
        Response::ok(Db::query('SELECT * FROM payment_orders WHERE openid = ? ORDER BY created_at DESC', [$openid]));
    }

    public function query(): void
    {
        $orderNo = (string) ($_GET['order_no'] ?? '');
        if ($orderNo === '') {
            Response::fail('缺少order_no', 400);
        }
        $row = Db::find('SELECT * FROM payment_orders WHERE order_no = ?', [$orderNo]);
        $row ? Response::ok($row) : Response::fail('订单不存在', 404);
    }

    public function unifiedorder(): void
    {
        $body = json_decode(file_get_contents('php://input') ?: '[]', true) ?: [];
        foreach (['service_id', 'openid', 'total_fee'] as $required) {
            if (empty($body[$required])) {
                Response::fail('缺少' . $required, 400);
            }
        }

        $service = Db::find('SELECT * FROM services WHERE id = ? AND status = 1', [(int) $body['service_id']]);
        if (!$service) {
            Response::fail('服务不存在', 404);
        }

        $orderNo = 'P' . date('YmdHis') . random_int(1000, 9999);
        Db::exec(
            'INSERT INTO payment_orders (order_no, service_id, service_name, total_fee, openid, status) VALUES (?, ?, ?, ?, ?, "pending")',
            [$orderNo, (int) $service['id'], $service['name'], (float) $body['total_fee'], $body['openid']]
        );

        Response::ok(['order_no' => $orderNo, 'prepay_id' => '', 'payParams' => []]);
    }
}
