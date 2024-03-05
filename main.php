<?php
/**
 * test
 * php main.php create test/controller/IndexController:test/Model/UserModel
 */
require_once __DIR__."/vendor/autoload.php";

use Easycrud\Create\Controller;
use Easycrud\Create\Model;

$param = '';

try {
    // 获取参数
    if ($argc >= 2) {
        $param = $argv[2];
        $controller = new Controller($param);
        $controller->run();
        // $model = new Model($param);
        // $model->run();
    } else {
        return;
    }
} catch (\Throwable $th) {
    print_r($th->getMessage());
}
