<?php
namespace app\controller\api;

use app\common\Config;
use app\common\Db;
use app\common\Response;

class User
{
    public function login(): void
    {
        $body = json_decode(file_get_contents('php://input') ?: '[]', true) ?: [];
        $code = $body['code'] ?? '';
        if ($code === '') {
            Response::fail('缺少code参数', 400);
        }

        $openid = $this->wechatOpenid($code) ?: ('mock_openid_' . md5($code));
        $user = Db::find('SELECT * FROM users WHERE openid = ?', [$openid]);
        if (!$user) {
            $id = Db::insertId('INSERT INTO users (openid) VALUES (?)', [$openid]);
            $user = Db::find('SELECT * FROM users WHERE id = ?', [$id]);
        }

        Response::ok([
            'user_id' => (int) $user['id'],
            'openid' => $user['openid'],
            'nickname' => $user['nickname'],
            'avatar' => $user['avatar'],
            'phone' => $user['phone'],
        ]);
    }

    public function profile(): void
    {
        $userId = (int) ($_GET['user_id'] ?? 0);
        if (!$userId) {
            Response::fail('缺少user_id', 400);
        }
        $row = Db::find('SELECT id, nickname, avatar, phone, gender FROM users WHERE id = ?', [$userId]);
        $row ? Response::ok($row) : Response::fail('用户不存在', 404);
    }

    public function updateProfile(): void
    {
        $body = json_decode(file_get_contents('php://input') ?: '[]', true) ?: [];
        $userId = (int) ($body['user_id'] ?? 0);
        if (!$userId) {
            Response::fail('缺少user_id', 400);
        }

        $fields = [];
        $params = [];
        foreach (['nickname', 'avatar', 'phone', 'gender'] as $field) {
            if (array_key_exists($field, $body)) {
                $fields[] = "$field = ?";
                $params[] = $body[$field];
            }
        }
        if (!$fields) {
            Response::fail('没有需要更新的字段', 400);
        }

        $params[] = $userId;
        Db::exec('UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = ?', $params);
        Response::ok(['updated' => true]);
    }

    private function wechatOpenid(string $code): string
    {
        $appId = Config::wxAppId();
        $secret = Config::wxSecret();
        if ($appId === '' || $secret === '') {
            return '';
        }

        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . urlencode($appId)
            . '&secret=' . urlencode($secret)
            . '&js_code=' . urlencode($code)
            . '&grant_type=authorization_code';

        $context = stream_context_create(['http' => ['timeout' => 5]]);
        $json = @file_get_contents($url, false, $context);
        if (!$json) {
            return '';
        }
        $data = json_decode($json, true);
        return (string) ($data['openid'] ?? '');
    }
}
