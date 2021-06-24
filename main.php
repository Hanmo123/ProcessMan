<?php

use ProcessMan\Controller;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/ProcessMan/Autoloader.php';

$Controller = new Controller(); //实例化控制器对象
$Controller->onStartEvent(function () {
    global $Controller;

    $Controller->createProcess('php _exec.php', function ($proc) {
        // 创建完成事件
        // 如需获取进程对象请使用此处的 $proc
        echo $proc->test();
    }, function ($proc, $data) {
        // 标准输入事件
        // 返回 false 阻止标准输入
        return true;
    }, function ($proc, $data) {
        // 标准输出事件
        echo $proc->test(), ' ';
        $proc->input(mt_rand());
        echo $data . PHP_EOL;
    });
    // 创建进程
});

Controller::start();
// 启动控制器
