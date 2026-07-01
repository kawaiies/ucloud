<?php
require __DIR__ . '/../vendor/autoload.php';

$app = new think\App();
$app->http->run()->send();
