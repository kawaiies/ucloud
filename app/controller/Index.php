<?php
namespace app\controller;

use app\common\Response;

class Index
{
    public function index(): void
    {
        Response::ok(['service' => 'thinkphp-backend', 'timestamp' => time()]);
    }

    public function health(): void
    {
        Response::ok(['service' => 'thinkphp-backend', 'timestamp' => time()]);
    }
}
